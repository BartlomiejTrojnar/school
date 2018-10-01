<?php
namespace App\Http\Controllers;
use App\Models\Grade;
use App\Repositories\GradeRepository;
use App\Repositories\SchoolRepository;
use App\Repositories\StudentClassRepository;
use App\Models\GroupClass;
use App\Repositories\GroupClassRepository;
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


    public function show($id, $view, GradeRepository $gradeRepo, StudentClassRepository $studentClassRepo)
    {
        $grade = $gradeRepo -> find($id);
        $previous = $gradeRepo->PreviousRecordId($id);
        $next = $gradeRepo->NextRecordId($id);

        switch($view) {
             case 'showGroups':
               $class = 'groupClass';
               $recordNames = 'groupClasses';
//               $records = $groupClassRepo -> getAll() -> where('class_id', $id);
                 $c = new GroupClass();
               $repo = new GroupClassRepository($c);
               //$records = $this -> getGroups($id, $repo);
               exit;
             break;
             default:
               $class = 'studentClass';
               $recordNames = 'studentClasses';
               $records = $studentClassRepo -> getAll() -> where('grade_id', $id);
             break;
        }

        return view('grade.show', ["grade"=>$grade, "previous"=>$previous, "next"=>$next])
             ->nest('nestView', $class.'.index', ["$recordNames"=>$records]);
    }
    public function getGroups(GroupClassRepository $groupClassRepo)
    {
        $id=100;
         return $groupClassRepo -> getAll() -> where('class_id', $id);
    }



    public function show2(Grade $klasa, GradeRepository $gradeRepo, StudentClassRepository $studentClassRepo)
    {
        $studentClasses = $studentClassRepo -> getAll() -> where('grade_id', $klasa->id);
        $previous = $gradeRepo->PreviousRecordId($klasa->id);
        $next = $gradeRepo->NextRecordId($klasa->id);
        return view('grade.show', ["grade"=>$klasa, "previous"=>$previous, "next"=>$next])
             ->nest('nestView', 'studentClass.index', ["studentClasses"=>$studentClasses]);
    }
    public function showGroups($id, GradeRepository $gradeRepo, GroupClassRepository $groupClassRepo)
    {
        $groupClasses = $groupClassRepo -> getAll() -> where('class_id', $id);
        $grade = $gradeRepo -> find($id);
        $previous = $gradeRepo->PreviousRecordId($id);
        $next = $gradeRepo->NextRecordId($id);
        return view('grade.show', ["grade"=>$grade, "previous"=>$previous, "next"=>$next])
             ->nest('nestView', 'groupClass.index', ["groupClasses"=>$groupClasses]);
    }
    public function showTeachers($id, GroupClassRepository $groupClassRepo)
    {
        $groupClasses = $groupClassRepo -> getAll() -> where('class_id', $id);
        return view('groupClass.index', ["groupClasses"=>$groupClasses]);
    }
    public function showLessonPlans($id, GroupClassRepository $groupClassRepo)
    {
        $groupClasses = $groupClassRepo -> getAll() -> where('class_id', $id);
        return view('groupClass.index', ["groupClasses"=>$groupClasses]);
    }
    public function showStudents($id, GradeRepository $gradeRepo, StudentClassRepository $studentClassRepo)
    {
        $studentClasses = $studentClassRepo -> getAll() -> where('grade_id', $id);
        $grade = $gradeRepo -> find($id);
        $previous = $gradeRepo->PreviousRecordId($id);
        $next = $gradeRepo->NextRecordId($id);
        return view('grade.show', ["grade"=>$grade, "previous"=>$previous, "next"=>$next])
             ->nest('nestView', 'studentClass.index', ["studentClasses"=>$studentClasses]);
    }
    public function showEnlargements($id, StudentClassRepository $studentClassRepo)
    {
        $studentClasses = $studentClassRepo -> getAll() -> where('class_id', $id);
        return view('studentClass.index', ["studentClasses"=>$studentClasses]);
    }
    public function showRatings($id, StudentClassRepository $studentClassRepo)
    {
        $studentClasses = $studentClassRepo -> getAll() -> where('class_id', $id);
        return view('studentClass.index', ["studentClasses"=>$studentClasses]);
    }
    public function showTasks($id, StudentClassRepository $studentClassRepo)
    {
        $studentClasses = $studentClassRepo -> getAll() -> where('class_id', $id);
        return view('studentClass.index', ["studentClasses"=>$studentClasses]);
    }

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
