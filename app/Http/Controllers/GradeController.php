<?php
namespace App\Http\Controllers;
use App\Models\Grade;
//use App\Models\GroupClass;
//use App\Models\StudentClass;
use App\Repositories\GradeRepository;
use App\Repositories\SchoolRepository;
//use App\Repositories\GroupClassRepository;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    public function index(GradeRepository $gradeRepo)
    {
        for($i=0; $i<4; $i++)
          $orderBy[$i] = session()->get("GradeOrderBy[$i]");

        $grades = $gradeRepo->getPaginate($orderBy);
        return view('grade.index', ["grades"=>$grades]);
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
        return redirect( route('klasa.index') );
    }

    public function create(SchoolRepository $schoolRepo)
    {
        $schools = $schoolRepo->getAll();
        return view('grade.create')
             ->nest('schoolSelectField', 'school.selectField', ["schools"=>$schools, "selectedSchool"=>1]);
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

    public function show($id, $view='', GradeRepository $gradeRepo)
    {
        if(empty(session()->get('gradeView')))  session()->put('gradeView', 'showInfo');
        if($view)  session()->put('gradeView', $view);
        $grade = $gradeRepo -> find($id);
        $previous = $gradeRepo -> PreviousRecordId($id);
        $next = $gradeRepo -> NextRecordId($id);

        switch(session()->get('gradeView')) {
             case 'showInfo':
               return view('grade.showInfo', ["grade"=>$grade, "previous"=>$previous, "next"=>$next]);
               exit;
             break;
             case 'showStudents':
               $studentClasses = $gradeRepo -> find($id) -> students;
               return view('grade.showStudents', ["grade"=>$grade, "studentClasses"=>$studentClasses, "previous"=>$previous, "next"=>$next]);
               exit;
             break;
             case 'showStudents2':
               return view('grade.showStudents2', ["grade"=>$grade, "previous"=>$previous, "next"=>$next]);
               exit;
             break;
             case 'showEnlargements':
               return view('grade.showEnlargements', ["grade"=>$grade, "previous"=>$previous, "next"=>$next]);
               exit;
             break;
             case 'showGroups':
               return view('grade.showGroups', ["grade"=>$grade, "previous"=>$previous, "next"=>$next]);
               exit;
             break;
             case 'showLessonPlan':
               return view('grade.showLessonPlan', ["grade"=>$grade, "previous"=>$previous, "next"=>$next]);
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
               return view('grade.showDeclarations', ["grade"=>$grade, "previous"=>$previous, "next"=>$next]);
               exit;
             break;
             case 'showTasks':
               return view('grade.showTasks', ["grade"=>$grade, "previous"=>$previous, "next"=>$next]);
               exit;
             break;
             default:
               printf('<p style="background: #bb0; color: #f00; font-size: x-large; text-align: center; border: 3px solid red; padding: 5px;">Widok %s nieznany</p>', $view);
               exit;
             break;
        }
    }
/*    public function showStudents($id, GradeRepository $gradeRepo)
    {
        $studentClasses = StudentClass::all() -> where('grade_id', $id);
        return view('grade.showStudents', ["grade"=>$this->grade, "studentClasses"=>$studentClasses, "previous"=>$this->previous, "next"=>$this->next])
             ->nest('gradeMenu', 'grade.menu', ["grade"=>$this->grade]);
    }
*/

    public function edit(Grade $klasa, SchoolRepository $schoolRepo)
    {
        $schools = $schoolRepo->getAll();
        return view('grade.edit', ["grade"=>$klasa])
             ->nest('schoolSelectField', 'school.selectField', ["schools"=>$schools, "selectedSchool"=>$klasa->school_id]);
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
}
