<?php
// ------------------------ (C) mgr inż. Bartłomiej Trojnar; 26.02.2022 ------------------------ //
namespace App\Http\Controllers;
use App\Models\Grade;
use App\Repositories\GradeRepository;

use App\Models\TaskRating;
use App\Models\Teacher;
use App\Repositories\DeclarationRepository;
use App\Repositories\GroupRepository;
use App\Repositories\LessonPlanRepository;
use App\Repositories\SchoolRepository;
use App\Repositories\SchoolYearRepository;
use App\Repositories\StudentGradeRepository;
use App\Repositories\StudentNumberRepository;
use App\Repositories\SubjectRepository;
use App\Repositories\TaskRatingRepository;
use App\Repositories\TeacherRepository;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    public function index(GradeRepository $gradeRepo, SchoolRepository $schoolRepo, SchoolYearRepository $schoolYearRepo)   {
        $schoolYearSelected = session()->get('schoolYearSelected');
        if($schoolYearSelected) {
            $schoolYear = $schoolYearRepo -> find($schoolYearSelected);
            $year = substr($schoolYear->date_end, 0, 4);
        }
        else $year=0;
        $schoolSelected = session()->get('schoolSelected');
        $grades = $gradeRepo -> getFilteredAndSortedAndPaginate($year, $schoolSelected);
        $schools = $schoolRepo -> getAllSorted();
        $schoolSelectField = view('school.selectField', ["schools"=>$schools, "schoolSelected"=>$schoolSelected]);
        $schoolYears = $schoolYearRepo -> getAllSorted();
        $schoolYearSelectField = view('schoolYear.selectField', ["schoolYears"=>$schoolYears, "schoolYearSelected"=>$schoolYearSelected, "name"=>"school_year_id"]);

        $tableForIndex = view('grade.table', ["grades"=>$grades, "schoolSelectField"=>$schoolSelectField, "schoolYearSelectField"=>$schoolYearSelectField, "year"=>$year, "links"=>true]);
        return view('grade.index', ["grades"=>$grades, "tableForIndex"=>$tableForIndex]);
    }

    public function orderBy($column)    {
        if(session()->get('GradeOrderBy[0]') == $column)
          if(session()->get('GradeOrderBy[1]') == 'desc')
            session()->put('GradeOrderBy[1]', 'asc');
          else
            session()->put('GradeOrderBy[1]', 'desc');
        else
        {
          session()->put('GradeOrderBy[2]', session()->get('GradeOrderBy[0]'));
          session()->put('GradeOrderBy[0]', $column);
          session()->put('GradeOrderBy[3]', session()->get('GradeOrderBy[1]'));
          session()->put('GradeOrderBy[1]', 'asc');
        }
        return redirect( $_SERVER['HTTP_REFERER'] );
    }

    public function create(Request $request, SchoolRepository $schoolRepo) {
        if($request->version == "forIndex")     return $this -> createRow($schoolRepo);
        if($request->version == "forSchool")    return view('grade.createForSchool', ["school_id"=>$request->school_id]);
        if($request->version == "forSchoolYear") return $this -> createRow($schoolRepo);
        return $request->version;
    }

    private function createRow($schoolRepo) {
        $schools = $schoolRepo -> getAllSorted();
        $schoolSelectField = view('school.selectField', ["schools"=>$schools, "schoolSelected"=>9]);
        return view('grade.create', ["schoolSelectField"=>$schoolSelectField]);
    }

    public function store(Request $request) {
        $this -> validate($request, [
          'year_of_beginning' => 'required|integer|min:1900',
          'year_of_graduation' => 'required|integer|min:1905',
          'symbol' => 'max:2',
          'school_id' => 'required',
        ]);

        $grade = new Grade;
        $grade->year_of_beginning = $request->year_of_beginning;
        $grade->year_of_graduation = $request->year_of_graduation;
        $grade->symbol = $request->symbol;
        $grade->school_id = $request->school_id;
        $grade -> save();
        return $grade->id;
    }

    public function change($id) {  session()->put('gradeSelected', $id);  }

    public function show($id, GradeRepository $gradeRepo, SchoolYearRepository $syR, StudentGradeRepository $sgR, StudentNumberRepository $snR, GroupRepository $gR,
            LessonPlanRepository $lpR, DeclarationRepository $dR, SubjectRepository $subR, teacherRepository $tR, $view='') {
        if( empty(session()->get('gradeView')) )  session()->put('gradeView', 'showInfo');
        if($view)  session()->put('gradeView', $view);
        if(!empty($id)) {
            $this->grade = $gradeRepo -> find($id);
            session()->put('gradeSelected', $id);    
        }

        $schoolYearSelected = session()->get('schoolYearSelected');
        if($schoolYearSelected) {
            $schoolYear = $syR -> find($schoolYearSelected);
            $year = substr($schoolYear->date_end, 0, 4);
        }
        else $year=0;
        $schoolSelected = session()->get('schoolSelected');
        $grades = $gradeRepo -> getFilteredAndSorted($year, $schoolSelected);
        list($this->previous, $this->next) = $gradeRepo -> nextAndPreviousRecordId($grades, session()->get('gradeSelected'));

        // pobranie informacji o roku szkolnym (aby wyświetlać rocznik klasy, jeżeli jest wybrany)
        $this->year = 0;
        if( !empty(session()->get('schoolYearSelected')) ) {
            $schoolYear = $syR -> find(session()->get('schoolYearSelected'));
            $this->year = substr($schoolYear->date_end, 0, 4);
        }

        switch(session()->get('gradeView')) {
            case 'showInfo':        return $this -> showInfo();
            case 'showStudents':    return $this -> showStudents($syR, $sgR);
            case 'showStudentsAll': return $this -> showStudentsAll();
            case 'showNumbers':     return $this -> showNumbers($syR, $snR);
            case 'showGroups':      return $this -> showGroup($subR, $tR, $gR);
            case 'showLessonPlan':  return $this -> showLessonPlan($gR, $lpR);
            case 'showTeachers':    return $this -> showTeachers();
            case 'showDeclarations':    return $this -> showDeclarations($dR);
            case 'showTasks':    return $this -> showTasks();
/*
          case 'showEnlargements':
              return view('grade.showEnlargements', ["grade"=>$grade, "previous"=>$previous, "next"=>$next]);
              exit;
          break;
          case 'showRatings':
              return view('grade.showRatings', ["grade"=>$grade, "previous"=>$previous, "next"=>$next]);
              exit;
          break;
*/
          default:
              printf('<p style="background: #bb0; color: #f00; font-size: x-large; text-align: center; border: 3px solid red; padding: 5px;">Widok %s nieznany</p>', session()->get('gradeView'));
        }
    }

    private function showInfo() {
        $subView = view('grade.showInfo', ["grade"=>$this->grade]);
        return view('grade.show', ["grade"=>$this->grade, "year"=>$this->year, "previous"=>$this->previous, "next"=>$this->next,
            "css"=>"", "js"=>"", "subView"=>$subView]);
    }

    private function showStudents($schoolYearRepo, $studentGradeRepo) { // funkcja przygotowująca i wyświetlająca widok przynależności uczniów do klasy
        $start = session()->get('dateView');
        $end = session()->get('dateEnd');
        if(!$end)   $end = $start;
        $schoolYearEnd = $schoolYearRepo -> getSchoolYearIdForDate($this->grade->year_of_graduation.'-01-01');

        $grades[0] = $this->grade->id;
        $studentGrades = $studentGradeRepo -> getStudentsFromGrades($grades);
        $subView = view('studentGrade.tableForGrade', ["subTitle"=>"uczniowie w klasie", "studentGrades"=>$studentGrades, "yearOfStudy"=>$this->year, "grade"=>$this->grade,
            "schoolYearEnd"=>$schoolYearEnd, "start"=>$start, "end"=>$end]);

        $css = "grade/studentGrades.css";
        $js = "grade/studentGrades.js";
        return view('grade.show', ["grade"=>$this->grade, "year"=>$this->year, "previous"=>$this->previous, "next"=>$this->next, "css"=>$css, "js"=>$js, "subView"=>$subView]);
    }

    private function showStudentsAll() {  // funkcja przygotowująca i wyświetlająca widok danych osobowych wszystkich uczniów, którzy w zadanym dniu należą do klasy
        foreach($this->grade -> students as $studentClass) {
            if( $studentClass->start <= session()->get('dateView') && $studentClass->end >= session()->get('dateView') )
              $students[] = $studentClass->student;
            else $studentsOutOfDate[] = $studentClass->student;
         }
         if(empty($students)) $students=0;
         if(empty($studentsOutOfDate)) $studentsOutOfDate=0;

         $selectedSex = 'kobieta';
         $sexSelectField = view('student.sexSelectField', ["sex"=>$selectedSex]);
         $studentsTable = view('student.table', ["grade"=>$this->grade, "students"=>$students, "subTitle"=>"aktualni uczniowie klasy", "showDateView"=>true, "sexSelectField"=>$sexSelectField]);
         $studentsOutOfDateTable = view('student.table', ["grade"=>$this->grade, "students"=>$studentsOutOfDate, "subTitle"=>"pozostali uczniowie klasy", "showDateView"=>false]);

         return view('grade.show', ["grade"=>$this->grade, "year"=>$this->year, "previous"=>$this->previous, "next"=>$this->next, "css"=>"", "js"=>"", "subView"=>$studentsTable, "subView2"=>$studentsOutOfDateTable]);
    }

    private function showNumbers($schoolYearRepo, $studentNumberRepo) {
        $schoolYears = $schoolYearRepo -> getAllSorted();
        if(session()->get('schoolYearSelected')) {
            $schoolYear = $schoolYearRepo -> find( session()->get('schoolYearSelected') );
            $schoolYearSelectField = view('schoolYear.selectField', ["schoolYears"=>$schoolYears, "schoolYearSelected"=>$schoolYear->id, "name"=>"school_year_id" ]);
            $studentNumbers = $studentNumberRepo -> getGradeNumbersForSchoolYear($this->grade->id, $schoolYear->id);
        }
        else {
            $schoolYear = $schoolYearRepo -> find( $schoolYearRepo -> getSchoolYearIdForDate(date('Y-m-d')) );
            $schoolYearSelectField = view('schoolYear.selectField', ["schoolYears"=>$schoolYears, "schoolYearSelected"=>0, "name"=>"school_year_id" ]);
            $studentNumbers = $studentNumberRepo -> getGradeNumbers($this->grade->id);
        }
        $yearOfStudy = substr($schoolYear->date_end,0,4) - ($this->grade->year_of_beginning+1);
        if(substr($schoolYear->date_end,5,2)) $yearOfStudy++;

        $tableForGrade = view('studentNumber.tableForGrade', ["schoolYearSelectField"=>$schoolYearSelectField, "studentNumbers"=>$studentNumbers, "grade"=>$this->grade]);
        $count = count($studentNumbers);
        $sectionForGrade = view('studentNumber.sectionForGrade', ["grade"=>$this->grade, "tableForGrade"=>$tableForGrade, "count"=>$count, "yearOfStudy"=>$yearOfStudy]);
        $css = "grade/studentNumbers.css";
        $js = "grade/studentNumbers.js";
        return view('grade.show', ["grade"=>$this->grade, "css"=>$css, "js"=>$js, "previous"=>$this->previous, "next"=>$this->next, "year"=>$this->year, "subView"=>$sectionForGrade]);
    }

    private function showGroup($subjectRepo, $teacherRepo, $groupRepo) {
        $subjectSelected = session()->get('subjectSelected');
        $subjects = $subjectRepo -> getActualAndSorted();
        $subjectSelectField = view('subject.selectField', ["subjects"=>$subjects, "subjectSelected"=>$subjectSelected]);

        $levels = array('rozszerzony', 'podstawowy');
        $levelSelected = session()->get('levelSelected');
        $levelSelectField = view('layouts.levelSelectField', ["levels"=>$levels, "levelSelected"=>$levelSelected]);

        $teacherSelected = session()->get('teacherSelected');
        $teachers = $teacherRepo -> getAll();
        $teacherSelectField = view('teacher.selectField', ["teachers"=>$teachers, "teacherSelected"=>$teacherSelected]);

        $start = session() -> get('dateView');
        if(!empty(session() -> get('dateEnd'))) $end = session() -> get('dateEnd'); else $end=$start;
        $groups = $groupRepo -> getFilteredAndSorted($this->grade->id, $subjectSelected, $levelSelected, $start, $end, $teacherSelected);

        $groupTable = view('group.table', ["version"=>"forGrade", "subTitle"=>"grupy klasy", "groups"=>$groups, "links"=>true, "start"=>$start, "end"=>$end, "grade_id"=>$this->grade->id,
            "gradeSelectField"=>"", "subjectSelectField"=>$subjectSelectField, "levelSelectField"=>$levelSelectField, "teacherSelectField"=>$teacherSelectField]);
        $js = "group/operations.js";

        return view('grade.show', ["grade"=>$this->grade, "year"=>$this->year, "previous"=>$this->previous, "next"=>$this->next, "css"=>"", "js"=>$js, "subView"=>$groupTable]);
    }

    private function showLessonPlan($groupRepo, $lessonPlanRepo) {
        $gradeLessons = $lessonPlanRepo -> getGradeLessons($this->grade->id);
        $dateView = session()->get('dateView'); if($dateView=="") $dateView = date('Y-m-d');
        $groups = $groupRepo -> getGradeGroups($this->grade->id);
        $studyYear = substr($dateView,0,4) - $this->grade->year_of_beginning;
        $gradePlan = view('lessonPlan.gradePlan', ["gradeLessons"=>$gradeLessons, "groups"=>$groups, "grade"=>$this->grade, "dateView"=>$dateView, "studyYear"=>$studyYear]);
        $js = "lessonPlan/forGrade.js";
        return view('grade.show', ["grade"=>$this->grade, "year"=>$this->year, "previous"=>$this->previous, "next"=>$this->next,  "css"=>"", "js"=>$js, "subView"=>$gradePlan]);
    }

    private function showTeachers() {
        $teacherRepo = new TeacherRepository(new Teacher);
        $teachers = $teacherRepo -> getTeachersForGrade($this->grade->id);

        $teacherTable = view('teacher.shortTable', ["teachers"=>$teachers, "subTitle"=>"nauczyciele klasy"]);

        return view('grade.show', ["grade"=>$this->grade, "year"=>$this->year, "previous"=>$this->previous, "next"=>$this->next,
            "css"=>"", "js"=>"", "subView"=>$teacherTable]);
    }

    private function showTasks() {
        $taskRatingRepo = new TaskRatingRepository(new TaskRating);
        $taskRatings = $taskRatingRepo -> getGradeTaskRatings($this->grade->id);
        $diaryYesNoSelected = session() -> get('diaryYesNoSelected');
        $diarySelectField = view('layouts.yesNoSelectField', ["fieldName"=>"diaryYesNo", "valueSelected"=>$diaryYesNoSelected]);

        $taskRatingTable = view('taskRating.table', ["grade"=>$this->grade, "taskRatings"=>$taskRatings, "subTitle"=>"zadania w klasie", "diarySelectField"=>$diarySelectField, "task"=>""]);

        return view('grade.show', ["grade"=>$this->grade, "previous"=>$this->previous, "next"=>$this->next,
            "css"=>"", "js"=>"", "subView"=>$taskRatingTable]);
    }

    private function showDeclarations($declarationRepo) {
        $sessionSelected = session()->get('sessionSelected');
        $declarations = $declarationRepo -> getFilteredAndSorted($sessionSelected, $this->grade->id, 0);
        $declarationsTable = view('declaration.tableForGrade', ["declarations"=>$declarations]);
        $css = "";
        $js = "declaration/operations.js";
        return view('grade.show', ["grade"=>$this->grade, "year"=>$this->year, "previous"=>$this->previous, "next"=>$this->next, "subView"=>$declarationsTable, "css"=>$css, "js"=>$js]);
    }

    public function edit(Request $request, Grade $grade, SchoolRepository $schoolRepo) {
        $grade = $grade -> find($request->id);
        if($request->version == "forIndex")     return $this -> editForIndex($grade, $schoolRepo, $request->lp);
        if($request->version == "forSchool")    return view('grade.editForSchool', ["grade"=>$grade, "lp"=>$request->lp]);
        return $request->version;
    }

    private function editForIndex($grade, $schoolRepo, $lp) {
        $schools = $schoolRepo -> getAllSorted();
        $schoolSelectField = view('school.selectField', ["schools"=>$schools, "schoolSelected"=>$grade->school_id]);
        return view('grade.edit', ["grade"=>$grade, "schoolSelectField"=>$schoolSelectField, "lp"=>$lp]);
    }

    public function update($id, Request $request, Grade $grade) {
        $grade = $grade -> find($id);
        $this -> validate($request, [
          'year_of_beginning' => 'required|integer|min:1900',
          'year_of_graduation' => 'required|integer|min:1905',
          'symbol' => 'max:2',
          'school_id' => 'required',
        ]);

        $grade->year_of_beginning = $request->year_of_beginning;
        $grade->year_of_graduation = $request->year_of_graduation;
        $grade->symbol = $request->symbol;
        $grade->school_id = $request->school_id;
        $grade -> save();
        return $grade->id;
    }

    public function destroy($id, Grade $grade)  {
        $grade = $grade -> find($id);
        $grade -> delete();
        return 1;
    }

    public function getDates($id, Grade $grade) {
        $grade = $grade -> find($id);
        for($i = $grade->year_of_beginning; $i<$grade->year_of_graduation; $i++) {
            $dates[] = $i."-09-01";
            $dates[] = ($i+1)."-08-31";
        }
        return $dates;
    }

    public function refreshRow(Request $request, GradeRepository $gradeRepo, SchoolYearRepository $schoolYearRepo) {
        $this->grade = $gradeRepo -> find($request->id);

        $schoolYearSelected = session()->get('schoolYearSelected');
        if($schoolYearSelected) {
            $schoolYear = $schoolYearRepo -> find($schoolYearSelected);
            $year = substr($schoolYear->date_end, 0, 4);
        }
        else $year=0;

        if($request->version == "forIndex")     return view('grade.row', ["grade"=>$this->grade, "year"=>$year, "lp"=>$request->lp]);
        if($request->version == "forSchool")    return view('grade.rowForSchool', ["grade"=>$this->grade, "year"=>$year, "lp"=>$lp]);
        return $request->version;
    }
}