<?php
namespace App\Http\Controllers;
use App\Models\Grade;
use App\Repositories\GradeRepository;

//use App\Models\LessonHour;
use App\Repositories\SchoolRepository;
use App\Repositories\StudentClassRepository;
use App\Repositories\TaskRatingRepository;
//use App\Models\GroupClass;
//use App\Models\StudentClass;
//use App\Repositories\GroupClassRepository;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    public function index(GradeRepository $gradeRepo, SchoolRepository $schoolRepo)
    {
        $schools = $schoolRepo->getAllSorted();
        $schoolSelected = session()->get('schoolSelected');
        $schoolSelectField = view('school.selectField', ["schools"=>$schools, "schoolSelected"=>$schoolSelected]);

        $grades = $gradeRepo -> getPaginateSorted();
        if( $schoolSelected ) {
            $grades = Grade::where('school_id', $schoolSelected);
            $grades = $gradeRepo -> sortAndPaginateRecords($grades);
        }
        return view('grade.index')
            -> nest('gradeTable', 'grade.table', ["grades"=>$grades, "links"=>true, "subTitle"=>"", "schoolSelectField"=>$schoolSelectField]);
    }

    public function orderBy($column)
    {
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

    public function create(SchoolRepository $schoolRepo)
    {
        $schools = $schoolRepo->getAllSorted();
        return view('grade.create')
             ->nest('schoolSelectField', 'school.selectField', ["schools"=>$schools, "schoolSelected"=>1]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
          'year_of_beginning' => 'required|integer|min:1900',
          'year_of_graduation' => 'required|integer|min:1905',
          'symbol' => 'max:1',
          'school_id' => 'required',
        ]);

        $grade = new Grade;
        $grade->year_of_beginning = $request->year_of_beginning;
        $grade->year_of_graduation = $request->year_of_graduation;
        $grade->symbol = $request->symbol;
        $grade->school_id = $request->school_id;
        $grade->save();

        return redirect($request->history_view);
    }

    public function show($id, $view='', GradeRepository $gradeRepo, StudentClassRepository $studentClassRepo, TaskRatingRepository $taskRatingRepo)
    {
        if(empty(session()->get('gradeView')))  session()->put('gradeView', 'showInfo');
        if($view)  session()->put('gradeView', $view);
        $grade = $gradeRepo -> find($id);
        $previous = $gradeRepo -> PreviousRecordId($id);
        $next = $gradeRepo -> NextRecordId($id);

        switch(session()->get('gradeView')) {
          case 'showInfo':
              return view('grade.show', ["grade"=>$grade, "previous"=>$previous, "next"=>$next])
                  -> nest('subView', 'grade.showInfo', ["grade"=>$grade]);
          break;
          case 'showStudents':
              $studentClasses = $studentClassRepo -> getGradeStudents($grade->id);
              $java_script = "studentClass.js";
              return view('grade.show', ["grade"=>$grade, "previous"=>$previous, "next"=>$next, "java_script"=>$java_script])
                  -> nest('subView', 'studentClass.table', ["grade"=>$grade, "studentClasses"=>$studentClasses, "subTitle"=>"uczniowie w klasie"]);
          break;
          case 'showStudentsAll':
              foreach($grade -> students as $studentClass) {
                 if( $studentClass->date_start <= session()->get('dateSession') && $studentClass->date_end >= session()->get('dateSession') )
                   $students[] = $studentClass->student;
                 else $studentsOutOfDate[] = $studentClass->student;
              }
              if(empty($students)) $students=0;
              if(empty($studentsOutOfDate)) $studentsOutOfDate=0;
              return view('grade.show', ["grade"=>$grade, "previous"=>$previous, "next"=>$next])
                  -> nest('subView', 'student.table', ["grade"=>$grade, "students"=>$students, "subTitle"=>"aktualni uczniowie klasy"])
                  -> nest('subView2', 'student.table', ["grade"=>$grade, "students"=>$studentsOutOfDate, "subTitle"=>"pozostali uczniowie klasy"]);
          break;
          case 'showTasks':
              $taskRatings = $taskRatingRepo -> getGradeTaskRatings($grade->id);
              return view('grade.show', ["grade"=>$grade, "previous"=>$previous, "next"=>$next])
                  -> nest('subView', 'taskRating.table', ["grade"=>$grade, "taskRatings"=>$taskRatings, "subTitle"=>"zadania w klasie"]);
          break;
/*
          case 'showEnlargements':
              return view('grade.showEnlargements', ["grade"=>$grade, "previous"=>$previous, "next"=>$next]);
              exit;
          break;
          case 'showGroups':
              return view('grade.showGroups', ["grade"=>$grade, "previous"=>$previous, "next"=>$next]);
              exit;
          break;
          case 'showLessonPlan':
              $lessonHours = LessonHour::where('day', 'poniedzia??ek') -> get();
              return view('grade.showLessonPlan', ["grade"=>$grade, "lessonHours"=>$lessonHours, "previous"=>$previous, "next"=>$next]);
              exit;
          break;
          case 'showTeachers':
              return view('grade.showTeachers', ["grade"=>$grade, "previous"=>$previous, "next"=>$next]);
              exit;
          break;
          case 'showRatings':
              return view('grade.showRatings', ["grade"=>$grade, "previous"=>$previous, "next"=>$next]);
              exit;
          break;
          case 'showDeclarations':
              $students = $grade -> students;
              foreach($students as $student)
                if( $student->student->declarations->count() )
                  $declarations = $student->student->declarations;
              if( empty($declarations) ) $declarations = 0;
              return view('grade.showDeclarations', ["grade"=>$grade, "declarations"=>$declarations, "previous"=>$previous, "next"=>$next]);
              exit;
          break;
*/
          case 'change':
              session()->put('gradeSelected', $id);
              return redirect( $_SERVER['HTTP_REFERER'] );
          break;
          default:
              printf('<p style="background: #bb0; color: #f00; font-size: x-large; text-align: center; border: 3px solid red; padding: 5px;">Widok %s nieznany</p>', session()->get('gradeView'));
              exit;
          break;
        }
    }

    public function edit(Grade $klasa, SchoolRepository $schoolRepo)
    {
        $schools = $schoolRepo->getAllSorted();
        return view('grade.edit', ["grade"=>$klasa])
             ->nest('schoolSelectField', 'school.selectField', ["schools"=>$schools, "schoolSelected"=>$klasa->school_id]);
    }

    public function update(Request $request, Grade $klasa)
    {
        $this->validate($request, [
          'year_of_beginning' => 'required|integer|min:1900',
          'year_of_graduation' => 'required|integer|min:1905',
          'symbol' => 'max:1',
          'school_id' => 'required',
        ]);

        $klasa->year_of_beginning = $request->year_of_beginning;
        $klasa->year_of_graduation = $request->year_of_graduation;
        $klasa->symbol = $request->symbol;
        $klasa->school_id = $request->school_id;
        $klasa->save();

        return redirect($request->history_view);
    }

    public function destroy(Grade $klasa)
    {
        $klasa->delete();
        return redirect( $_SERVER['HTTP_REFERER'] );
    }

    public function getDates($id)
    {
        $grade = Grade::find($id);
        for($i = $grade->year_of_beginning; $i<$grade->year_of_graduation; $i++) {
            $daty[] = $i."-09-01";
            $daty[] = ($i+1)."-08-31";
        }
        return $daty;
    }
}