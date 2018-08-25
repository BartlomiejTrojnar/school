<?php
namespace App\Http\Controllers;
use App\Models\School;
use App\Repositories\SchoolRepository;
use Illuminate\Http\Request;

class SchoolController extends Controller
{
    public function index(SchoolRepository $schoolRepo)
    {
        for($i=0; $i<4; $i++)
          $orderBy[$i] = session()->get("SchoolOrderBy[$i]");

        $schools = $schoolRepo->getAll($orderBy);
        return view('school.index', ["schools"=>$schools]);
    }

    public function orderBy($column)
    {
        if(session()->get('SchoolOrderBy[0]') == $column)
          if(session()->get('SchoolOrderBy[1]') == 'desc')
            session()->put('SchoolOrderBy[1]', 'asc');
          else
            session()->put('SchoolOrderBy[1]', 'desc');
        else
        {
          session()->put('SchoolOrderBy[2]', session()->get('SchoolOrderBy[0]'));
          session()->put('SchoolOrderBy[0]', $column);
          session()->put('SchoolOrderBy[3]', session()->get('SchoolOrderBy[1]'));
          session()->put('SchoolOrderBy[1]', 'asc');
        }

        return redirect( route('szkola.index') );
    }

    public function create()
    {
        return view('school.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
          'name' => 'required|max:40',
          'id_OKE' => 'max:12',
        ]);

        $school = new School;
        $school->name = $request->name;
        $school->id_OKE = $request->id_OKE;
        $school->save();

        return redirect($request->history_view);
    }

    public function show(School $szkola, SchoolRepository $schoolRepo)
    {
        $grades = School::findOrFail($szkola->id)->grades;
        $previous = $schoolRepo->PreviousRecordId($szkola->id);
        $next = $schoolRepo->NextRecordId($szkola->id);
        return view('school.show', ["school"=>$szkola, "grades"=>$grades, "previous"=>$previous, "next"=>$next]);
    }

    public function edit(School $szkola)
    {
        return view('school.edit', ["school"=>$szkola]);
    }

    public function update(Request $request, School $szkola)
    {
        $this->validate($request, [
          'name' => 'required|max:40',
          'id_OKE' => 'max:12',
        ]);

        $szkola->name = $request->name;
        $szkola->id_OKE = $request->id_OKE;
        $szkola->save();

        return redirect($request->history_view);
    }

    public function destroy(School $szkola)
    {
        $szkola->delete();
        return redirect( $_SERVER['HTTP_REFERER'] );
    }
}