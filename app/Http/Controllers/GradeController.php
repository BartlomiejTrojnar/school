<?php
namespace App\Http\Controllers;
use App\Models\Grade;
use App\Repositories\GradeRepository;
use App\Repositories\SchoolRepository;
use App\Repositories\StudentClassRepository;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    public function index(GradeRepository $gradeRepo)
    {
        for($i=0; $i<4; $i++)
          $orderBy[$i] = session()->get("GradeOrderBy[$i]");

        $grades = $gradeRepo->getAll($orderBy);
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

    public function show(Grade $klasa, GradeRepository $gradeRepo, StudentClassRepository $studentClassRepo)
    {
        $studentClasses = $studentClassRepo->getAll()->where('grade_id', $klasa->id);
        $previous = $gradeRepo->PreviousRecordId($klasa->id);
        $next = $gradeRepo->NextRecordId($klasa->id);
        return view('grade.show', ["grade"=>$klasa, "studentClasses"=>$studentClasses, "previous"=>$previous, "next"=>$next]);
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
