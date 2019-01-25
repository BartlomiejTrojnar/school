<?php
namespace App\Http\Controllers;
use App\Models\Student;
use App\Repositories\StudentRepository;

use App\Repositories\SchoolYearRepository;
use App\Repositories\GradeRepository;
use App\Repositories\StudentClassRepository;
use App\Repositories\TaskRatingRepository;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index(StudentRepository $studentRepo, SchoolYearRepository $schoolYearRepo, GradeRepository $gradeRepo)
    {
        $students = Student::leftjoin('student_classes', 'students.id', '=', 'student_classes.student_id')
          -> select('students.*');
        if( session()->get('schoolYearSelected') ) {
            $schoolYear = $schoolYearRepo -> find( session()->get('schoolYearSelected') );
            $students = $students -> where('date_start', '>=', $schoolYear->date_start)
                                  -> where('date_end', '<=', $schoolYear->date_end);
        }
        if( session()->get('gradeSelected') )
            $students = $students -> where('grade_id', '=', session()->get('gradeSelected'));
        $students = $studentRepo -> sortAndPaginateRecords($students);

        $schoolYears = $schoolYearRepo->getAllSorted();
        $grades = $gradeRepo->getAllSorted();
        return view('student.index', ["students"=>$students])
            -> nest('schoolYearSelectField', 'schoolYear.selectField', ["schoolYears"=>$schoolYears, "schoolYearSelected"=>session()->get('schoolYearSelected'), "name"=>"schoolYear_id" ])
            -> nest('gradeSelectField', 'grade.selectField', ["grades"=>$grades, "gradeSelected"=>session()->get('gradeSelected') ]);
    }

    public function orderBy($column)
    {
        if(session()->get('StudentOrderBy[0]') == $column)
          if(session()->get('StudentOrderBy[1]') == 'desc')
            session()->put('StudentOrderBy[1]', 'asc');
          else
            session()->put('StudentOrderBy[1]', 'desc');
        else
        {
          session()->put('StudentOrderBy[4]', session()->get('StudentOrderBy[2]'));
          session()->put('StudentOrderBy[2]', session()->get('StudentOrderBy[0]'));
          session()->put('StudentOrderBy[0]', $column);
          session()->put('StudentOrderBy[5]', session()->get('StudentOrderBy[3]'));
          session()->put('StudentOrderBy[3]', session()->get('StudentOrderBy[1]'));
          session()->put('StudentOrderBy[1]', 'asc');
        }
        return redirect( $_SERVER['HTTP_REFERER'] );
    }

    public function create()
    {
        $selectedSex = 'kobieta';
        return view('student.create')
            ->nest('sexSelectField', 'student.sexSelectField', ["sex"=>$selectedSex]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
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
        $student->save();

        if($request->history_view == 'http://localhost/szkola/public/uczen/search_results') return redirect('uczen');
        return redirect($request->history_view);
    }

    public function show($id, $view='', StudentRepository $studentRepo, StudentClassRepository $studentClassRepo, TaskRatingRepository $taskRatingRepo)
    {
        session()->put('studentSelected', $id);
        if(empty(session()->get('studentView')))  session()->put('studentView', 'showInfo');
        if($view)  session()->put('studentView', $view);
        $student = $studentRepo -> find($id);
        $previous = $studentRepo -> PreviousRecordId($id);
        $next = $studentRepo -> NextRecordId($id);

        switch(session()->get('studentView')) {
          case 'showInfo':
              return view('student.show', ["student"=>$student, "previous"=>$previous, "next"=>$next])
                  -> nest('subView', 'student.showInfo', ["student"=>$student]);
          break;
          case 'showClasses':
              $subTitle = "Klasy ucznia";
              $studentClasses = $studentClassRepo -> getStudentGrades($student->id);
              return view('student.show', ["student"=>$student, "previous"=>$previous, "next"=>$next])
                  -> nest('subView', 'studentClass.table', ["student"=>$student, "subTitle"=>$subTitle, "studentClasses"=>$studentClasses]);
          break;
/*
          case 'showEnlargements':
              return view('student.showEnlargements', ["student"=>$student, "previous"=>$previous, "next"=>$next]);
              exit;
          break;
          case 'showGroups':
              return view('student.showGroups', ["student"=>$student, "previous"=>$previous, "next"=>$next]);
              exit;
          break;
          case 'showRatings':
              return view('student.showRatings', ["student"=>$student, "previous"=>$previous, "next"=>$next]);
              exit;
          break;
          case 'showDeclarations':
              $declarations = $student -> declarations;
              return view('student.showDeclarations', ["student"=>$student, "declarations"=>$declarations, "previous"=>$previous, "next"=>$next]);
          break;
          case 'showExams':
              return view('student.showExams', ["student"=>$student, "previous"=>$previous, "next"=>$next]);
              exit;
          break;
*/
          case 'showTasks':
              $subTitle = "Zadania ucznia";
              $taskRatings = $taskRatingRepo -> getStudentTaskRatings($student->id);
              return view('student.show', ["student"=>$student, "previous"=>$previous, "next"=>$next])
                  -> nest('subView', 'taskRating.table', ["student"=>$student, "subTitle"=>$subTitle, "taskRatings"=>$taskRatings]);
          break;
/*
          case 'showLessonPlan':
              $lessonHours = LessonHour::where('day', 'poniedziałek') -> get();
              return view('student.showLessonPlan', ["student"=>$student, "lessonHours"=>$lessonHours, "previous"=>$previous, "next"=>$next]);
              exit;
          break;
*/
          default:
              printf('<p style="background: #bb0; color: #f00; font-size: x-large; text-align: center; border: 3px solid red; padding: 5px;">Widok %s nieznany</p>', session()->get('studentView'));
              exit;
          break;
        }
    }

    public function edit(Student $uczen)
    {
        return view('student.edit', ["student"=>$uczen])
            ->nest('sexSelectField', 'student.sexSelectField', ["sex"=>$uczen->sex]);
    }

    public function update(Request $request, Student $uczen)
    {
        $this->validate($request, [
          'first_name' => 'required|max:20',
          'second_name' => 'max:12',
          'last_name' => 'required|max:18',
          'second_name' => 'max:15',
          'sex' => 'required',
          'pesel' => 'min:11|max:11|unique',
          'place_of_birth' => 'max:20',
        ]);

        $uczen->first_name = $request->first_name;
        $uczen->second_name = $request->second_name;
        $uczen->last_name = $request->last_name;
        $uczen->family_name = $request->family_name;
        $uczen->sex = $request->sex;
        $uczen->PESEL = $request->PESEL;
        $uczen->place_of_birth = $request->place_of_birth;
        $uczen->save();

        if($request->history_view == 'http://localhost/szkola/public/uczen/search_results') return redirect('uczen');
        return redirect($request->history_view);
    }

    public function destroy(Student $uczen)
    {
        $uczen->delete();
        if($_SERVER['HTTP_REFERER'] == 'http://localhost/szkola/public/uczen/search_results') return redirect('uczen');
        return redirect( $_SERVER['HTTP_REFERER'] );
    }

    public function search()
    {
        return view('student.search');
    }

    public function searchResults(Request $request, StudentRepository $studentRepo)
    {
        $students = $studentRepo -> getAllSorted();
        if($request->last_name) $students = $students -> where('last_name', '=', $request->last_name);
        if($request->first_name) $students = $students -> where('first_name', '=', $request->first_name);
        if($request->PESEL) $students = $students -> where('PESEL', '=', $request->PESEL);
        if($request->place_of_birth) $students = $students -> where('place_of_birth', '=', $request->place_of_birth);
        //$students = $studentRepo -> sortAndPaginateRecords($students);
        return view('student.searchResults', ["students"=>$students, "request"=>$request]);
    }
}
