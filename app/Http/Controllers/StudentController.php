<?php
// ------------------------ (C) mgr inż. Bartłomiej Trojnar; 06.05.2021 ------------------------ //
namespace App\Http\Controllers;
use App\Models\Student;
use App\Repositories\StudentRepository;

use App\Models\StudentHistory;
use App\Models\TaskRating;
use App\Repositories\GradeRepository;
use App\Repositories\GroupStudentRepository;
use App\Repositories\LessonPlanRepository;
use App\Repositories\SchoolYearRepository;
use App\Repositories\StudentGradeRepository;
use App\Repositories\StudentNumberRepository;
use App\Repositories\TaskRatingRepository;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index(StudentRepository $studentRepo, SchoolYearRepository $schoolYearRepo, GradeRepository $gradeRepo) {
        $students = Student::select('students.*')
            -> leftjoin('student_grades', 'students.id', '=', 'student_grades.student_id');
        if( session() -> get('schoolYearSelected') ) {
            $schoolYear = $schoolYearRepo -> find( session() -> get('schoolYearSelected') );
            $students = $students
                -> where('date_start', '>=', $schoolYear->date_start)
                -> where('date_end', '<=', $schoolYear->date_end);
        }
        if( session() -> get('gradeSelected') )     $students = $students -> where('grade_id', '=', session() -> get('gradeSelected'));
        $students = $studentRepo -> sortAndPaginateRecords($students);

        $schoolYears = $schoolYearRepo -> getAllSorted();
        $grades = $gradeRepo -> getAllSorted();
        $schoolYearSelectField = view('schoolYear.selectField', ["schoolYears"=>$schoolYears, "schoolYearSelected"=>session() -> get('schoolYearSelected'), "name"=>"schoolYear_id" ]);
        $gradeSelectField = view('grade.selectField', ["name"=>"grade_id", "grades"=>$grades, "gradeSelected"=>session() -> get('gradeSelected') ]);
        return view('student.index', ["students"=>$students, "schoolYearSelectField"=>$schoolYearSelectField, "gradeSelectField"=>$gradeSelectField]);
    }

    public function orderBy($column) {
        if(session()->get('StudentOrderBy[0]') == $column)
            if(session()->get('StudentOrderBy[1]') == 'desc')   session()->put('StudentOrderBy[1]', 'asc');
            else    session()->put('StudentOrderBy[1]', 'desc');
        else {
          session()->put('StudentOrderBy[4]', session()->get('StudentOrderBy[2]'));
          session()->put('StudentOrderBy[2]', session()->get('StudentOrderBy[0]'));
          session()->put('StudentOrderBy[0]', $column);
          session()->put('StudentOrderBy[5]', session()->get('StudentOrderBy[3]'));
          session()->put('StudentOrderBy[3]', session()->get('StudentOrderBy[1]'));
          session()->put('StudentOrderBy[1]', 'asc');
        }
        return redirect( $_SERVER['HTTP_REFERER'] );
    }

    public function create() {
        $selectedSex = 'kobieta';
        return view('student.create')
            -> nest('sexSelectField', 'student.sexSelectField', ["sex"=>$selectedSex]);
    }

    public function store(Request $request) {
        $this -> validate($request, [
          'first_name' => 'required|max:20',
          'second_name' => 'max:12',
          'last_name' => 'required|max:18',
          'second_name' => 'max:15',
          'sex' => 'required',
          'pesel' => 'min:11|max:11|unique',
          'place_of_birth' => 'max:20',
        ]);

        $student = new Student;
        $student->first_name = $request->first_name;
        $student->second_name = $request->second_name;
        $student->last_name = $request->last_name;
        $student->family_name = $request->family_name;
        $student->sex = $request->sex;
        $student->PESEL = $request->PESEL;
        $student->place_of_birth = $request->place_of_birth;
        $student -> save();

        session() -> put('studentSelected', $student->id);   // zapamiętanie właśnie dodanego ucznia

        if($request->history_view == 'http://localhost/szkola/public/uczen/search_results') return redirect('uczen');
        //return redirect($request->history_view);
        return redirect( 'uczen/' .$student->id. '/showGrades');
    }

    public function change($id) {  session()->put('studentSelected', $id);  }

    public function show($id, StudentRepository $studentRepo, SchoolYearRepository $schoolYearRepo, StudentGradeRepository $sgRepo, StudentNumberRepository $snRepo,
        GroupStudentRepository $groupStudentRepo, LessonPlanRepository $lessonPlanRepo, $view='') {
        session() -> put('studentSelected', $id);
        if(empty( session()->get('studentView') ))  session() -> put('studentView', 'info');
        if($view)  session() -> put('studentView', $view);
        $this->student = $studentRepo -> find($id);

        $students = Student::select('students.*')
            -> leftjoin('student_grades', 'students.id', '=', 'student_grades.student_id');
        if( session() -> get('gradeSelected') )     $students = $students -> where('grade_id', '=', session() -> get('gradeSelected'));
        $students = $studentRepo -> sortAndPaginateRecords($students);
        list($this->previous, $this->next) = $studentRepo -> nextAndPreviousRecordId($students, $id);

        // pobranie informacji o roku szkolnym (aby wyświetlać rocznik klasy, jeżeli jest wybrany)
        $this->year = 0;
        if( !empty(session()->get('schoolYearSelected')) ) {
            $schoolYear = $schoolYearRepo -> find(session()->get('schoolYearSelected'));
            $this->year = substr($schoolYear->date_end, 0, 4);
        }

        switch(session() -> get('studentView')) {
            case 'info':        return $this -> showInfo();
            case 'klasy':       return $this -> showGrades($sgRepo, $snRepo, $schoolYearRepo);
            case 'grupy':       return $this -> showGroups($groupStudentRepo, $schoolYearRepo);
            case 'planlekcji':  return $this -> showLessonPlan($lessonPlanRepo);
            case 'zadania':     return $this -> showTasks();
            case 'deklaracje':  return $this -> showDeclarations();
            case 'egzaminy':    return $this -> showExams();
/*
          case 'showEnlargements':
              return view('student.showEnlargements', ["student"=>$student, "previous"=>$previous, "next"=>$next]);
              exit;
          break;
          case 'showRatings':
              return view('student.showRatings', ["student"=>$student, "previous"=>$previous, "next"=>$next]);
              exit;
          break;
*/
            default:
                printf('<p style="background: #bb0; color: #f00; font-size: x-large; text-align: center; border: 3px solid red; padding: 5px;">Widok %s nieznany</p>', session()->get('studentView'));
        }
    }

    private function showInfo() {
        $css = "student/studentGrades.css";
        $js = "student/studentGrades.js";

        return view('student.show', ["student"=>$this->student, "css"=>$css, "js"=>$js, "previous"=>$this->previous, "next"=>$this->next])
            -> nest('subView', 'student.showInfo', ["student"=>$this->student]);
    }

    private function showGrades($studentGradeRepo, $studentNumberRepo, $schoolYearRepo) {
        $studentGrades = $studentGradeRepo -> getStudentGrades($this->student->id);
        $studentHistory = studentHistory :: where('student_id', $this->student->id) -> orderBy('date') -> get();
        $studentHistoryView = view('studentHistory.tableForStudent', ["student_id"=>$this->student->id, "studentHistory"=>$studentHistory]);

        $studentNumbersView = "tu będą numery ucznia";
        $studentNumbers = $studentNumberRepo -> getStudentNumbers($this->student->id);
        $year = date('Y');
        if( !empty(session()->get('schoolYearSelected')) ) {
            $schoolYear = $schoolYearRepo -> find(session()->get('schoolYearSelected'));
            $this->year = substr($schoolYear->date_end, 0, 4);
        }
        $studentNumbersView = view('studentNumber.tableForStudent', ["studentNumbers"=>$studentNumbers, "student"=>$this->student->id, "yearOfStudy"=>$year]);

        // przygotowanie widoku z tabelą klas ucznia
        $dateView = session() -> get('dateView');
        $studentGradeTable = view('studentGrade.tableForStudent', ["studentGrades"=>$studentGrades, "student"=>$this->student, "yearOfStudy"=>$this->year, "studentHistoryView"=>$studentHistoryView, "studentNumbersView"=>$studentNumbersView, "dateView"=>$dateView]);
        $css = "student/studentGrades.css";
        $js = "student/studentGrades.js";
        return view('student.show', ["student"=>$this->student, "css"=>$css, "js"=>$js, "previous"=>$this->previous, "next"=>$this->next, "subView"=>$studentGradeTable]);
    }

    private function showGroups($groupStudentRepo, $schoolYearRepo) {
        $dateView = session() -> get('dateView');

        // pobranie informacji o roku szkolnym (aby wyświetlać rocznik klasy, jeżeli jest wybrany)
        $year = 0;
        if( !empty(session()->get('schoolYearSelected')) ) {
            $schoolYear = $schoolYearRepo -> find(session()->get('schoolYearSelected'));
            $year = substr($schoolYear->date_end, 0, 4);
        }
        if($year==0) {
            $year=9999;
            foreach($this->student->grades as $sg) if( substr($sg->start, 0, 4) < $year) $year = substr($sg->start, 0, 4);
            $year++;
        }

        // znalezienie grup ucznia
        $studentGroups = $groupStudentRepo -> getStudentGroups($this->student->id);
        // lista grup do których uczeń należy
        $studentList = view('groupStudent.listForStudent', ["studentGroups"=>$studentGroups, "year"=>$year, "dateView"=>$dateView]);
        // lista grup do których uczeń należał w innym czasie
        $studentListOutside = view('groupStudent.listOfStudentGroupToWhichHeBelongedAtAnotherTime', ["studentGroups"=>$studentGroups, "year"=>$year, "dateView"=>$dateView]);
        // lista innych grup w klasie ucznia
        // znalezienie aktualnej klasy dla ucznia
        $grade_id=0;
        foreach($this->student->grades as $studentGrade)  {
            $grade_id = $studentGrade->grade_id;
            if($studentGrade->start<=$dateView && $studentGrade->end>=$dateView)  break;
        }
        if(!$grade_id) return 'Nie znaleziono klasy dla ucznia';
        // znalezienie innych grup w klasie ucznia
        if(empty($grade_id)) $grade_id=0;
        $groups = $groupStudentRepo -> getOtherGroupsInGrade($this->student->id, $grade_id, $dateView);
        $otherGroupsInGrade = view('groupStudent.otherGroupsInGradeForStudent', ["groups"=>$groups, "year"=>$this->year, "dateView"=>$dateView]);

        $css = "student/groups.css";
        $js = "student/groups.js";
        $listGroupsForStudent = view('groupStudent.sectionListsForStudent', ["dateView"=>$dateView, "student_id"=>$this->student->id, "studentList"=>$studentList, "studentListOutside"=>$studentListOutside, "otherGroupsInGrade"=>$otherGroupsInGrade]);
        return view('student.show', ["student"=>$this->student, "css"=>$css, "js"=>$js, "previous"=>$this->previous, "next"=>$this->next, "subView"=>$listGroupsForStudent]);
    }

    private function showLessonPlan($lessonPlanRepo) {
        $dateView = session()->get('dateView');
        $lessons = $lessonPlanRepo -> getStudentLessons($this->student->id, $dateView);
        $css = "";
        $js = "lessonPlan/forStudent.js";
        $studentPlan = view('lessonPlan.studentPlan', ["lessons"=>$lessons, "dateView"=>$dateView]);
        return view('student.show', ["student"=>$this->student, "css"=>$css, "js"=>$js, "previous"=>$this->previous, "next"=>$this->next, "subView"=>$studentPlan]);
    }

    private function showTasks() {
        $css = "/student/taskRating.css";
        $js = "/taskRating/forStudent.js";
        $subTitle = "Zadania ucznia";
        $taskRatingRepo = new TaskRatingRepository(new TaskRating);
        $taskRatings = $taskRatingRepo -> getStudentTaskRatings($this->student->id);
        $taskRatingsTable = view('taskRating.tableForStudent', ["student"=>$this->student, "subTitle"=>$subTitle, "taskRatings"=>$taskRatings]);
        return view('student.show', ["student"=>$this->student, "css"=>$css, "js"=>$js, "previous"=>$this->previous, "next"=>$this->next, "subView"=>$taskRatingsTable]);
    }

    private function showDeclarations() {
        $declarations = $this->student -> declarations;
        $subView = view('declaration.sectionForStudent', ["declarations"=>$declarations, "studentSelectField"=>'', "sessionSelectField"=>'', "gradeSelectField"=>""]);
        $css = "declaration/forStudent.css";
        $js = "declaration/forStudent.js";
        return view('student.show', ["student"=>$this->student, "css"=>$css, "js"=>$js, "previous"=>$this->previous, "next"=>$this->next, "subView"=>$subView]);
    }

    public function edit($id, StudentRepository $studentRepo) {
        $student = $studentRepo -> find($id);
        return view('student.edit', ["student"=>$student])
            -> nest('sexSelectField', 'student.sexSelectField', ["sex"=>$student->sex]);
    }

    public function update($id, Request $request, Student $student) {
        $student = $student -> find($id);
        $this -> validate($request, [
          'first_name' => 'required|max:20',
          'second_name' => 'max:12',
          'last_name' => 'required|max:18',
          'second_name' => 'max:15',
          'sex' => 'required',
          'pesel' => 'min:11|max:11|unique',
          'place_of_birth' => 'max:20',
        ]);

        $student->first_name = $request->first_name;
        $student->second_name = $request->second_name;
        $student->last_name = $request->last_name;
        $student->family_name = $request->family_name;
        $student->sex = $request->sex;
        $student->PESEL = $request->PESEL;
        $student->place_of_birth = $request->place_of_birth;
        $student -> save();

        if($request->history_view == 'http://localhost/szkola/public/uczen/search_results') return redirect('uczen');
        return redirect($request->history_view);
    }

    public function destroy($id, Student $student) {
        $student = $student -> find($id);
        $student -> delete();
        if($_SERVER['HTTP_REFERER'] == 'http://localhost/szkola/public/uczen/search_results') return redirect('uczen');
        return redirect( $_SERVER['HTTP_REFERER'] );
    }

    public function search() { return view('student.search'); }

    public function searchResults(Request $request, StudentRepository $studentRepo) {
        $students = $studentRepo -> getAllSorted();
        if($request->last_name)      $students = $students -> where('last_name', $request->last_name);
        if($request->first_name)     $students = $students -> where('first_name', $request->first_name);
        if($request->PESEL)          $students = $students -> where('PESEL', $request->PESEL);
        if($request->place_of_birth) $students = $students -> where('place_of_birth', $request->place_of_birth);
        //$students = $studentRepo -> sortAndPaginateRecords($students);
        return view('student.searchResults', ["students"=>$students, "request"=>$request]);
    }
}
