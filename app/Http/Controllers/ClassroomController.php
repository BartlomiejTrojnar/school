<?php
namespace App\Http\Controllers;
use App\Models\Classroom;
use App\Repositories\ClassroomRepository;
use Illuminate\Http\Request;

class ClassroomController extends Controller
{
    public function index(ClassroomRepository $classroomRepo)
    {
        for($i=0; $i<6; $i++)
          $orderBy[$i] = session()->get("ClassroomOrderBy[$i]");

        $classrooms = $classroomRepo->getAll($orderBy);
        return view('classroom.index', ["classrooms"=>$classrooms]);
    }

    public function orderBy($column)
    {
        if(session()->get('ClassroomOrderBy[0]') == $column)
          if(session()->get('ClassroomOrderBy[1]') == 'desc')
            session()->put('ClassroomOrderBy[1]', 'asc');
          else
            session()->put('ClassroomOrderBy[1]', 'desc');
        else
        {
          session()->put('ClassroomOrderBy[4]', session()->get('ClassroomOrderBy[2]'));
          session()->put('ClassroomOrderBy[2]', session()->get('ClassroomOrderBy[0]'));
          session()->put('ClassroomOrderBy[0]', $column);
          session()->put('ClassroomOrderBy[5]', session()->get('ClassroomOrderBy[3]'));
          session()->put('ClassroomOrderBy[3]', session()->get('ClassroomOrderBy[1]'));
          session()->put('ClassroomOrderBy[1]', 'asc');
        }
        return redirect( route('sala.index') );
    }

    public function create()
    {
        return view('classroom.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
          'name' => 'required|max:20',
          'capacity' => 'required|integer|max:100',
          'floor' => 'required|integer|min:0|max:2',
          'line' => 'required|integer|min:1|max:10',
          'column' => 'required|integer|min:1|max:10',
        ]);

        $classroom = new Classroom;
        $classroom->name = $request->name;
        $classroom->capacity = $request->capacity;
        $classroom->floor = $request->floor;
        $classroom->line = $request->line;
        $classroom->column = $request->column;
        $classroom->save();

        return redirect($request->history_view);
    }

    public function show(Classroom $sala, ClassroomRepository $classroomRepo)
    {
        $previous = $classroomRepo->PreviousRecordId($sala->id);
        $next = $classroomRepo->NextRecordId($sala->id);
        return view('classroom.show', ["classroom"=>$sala, "previous"=>$previous, "next"=>$next]);
    }

    public function edit(Classroom $sala)
    {
        return view('classroom.edit', ["classroom"=>$sala]);
    }

    public function update(Request $request, Classroom $sala)
    {
        $this->validate($request, [
          'name' => 'required|max:20',
          'capacity' => 'required|integer|max:100',
          'floor' => 'required|integer|min:0|max:2',
          'line' => 'required|integer|min:1|max:10',
          'column' => 'required|integer|min:1|max:10',
        ]);

        $sala->name = $request->name;
        $sala->capacity = $request->capacity;
        $sala->floor = $request->floor;
        $sala->line = $request->line;
        $sala->column = $request->column;
        $sala->save();

        return redirect($request->history_view);
    }

    public function destroy(Classroom $sala)
    {
        $sala->delete();
        return redirect( $_SERVER['HTTP_REFERER'] );
    }
}
