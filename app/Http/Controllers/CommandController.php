<?php
namespace App\Http\Controllers;
use App\Models\Command;
//use App\Repositories\CommandRepository;

use App\Repositories\TaskRepository;
//use App\Models\CommandRating;
use Illuminate\Http\Request;

class CommandController extends Controller
{
    public function index(CommandRepository $commandRepo)
    {
        $commands = $commandRepo->getAllSorted();
        return view('command.index', ["commands"=>$commands]);
    }

    public function orderBy($column)
    {
        if(session()->get('CommandOrderBy[0]') == $column)
          if(session()->get('CommandOrderBy[1]') == 'desc')
            session()->put('CommandOrderBy[1]', 'asc');
          else
            session()->put('CommandOrderBy[1]', 'desc');
        else
        {
          session()->put('CommandOrderBy[4]', session()->get('CommandOrderBy[2]'));
          session()->put('CommandOrderBy[2]', session()->get('CommandOrderBy[0]'));
          session()->put('CommandOrderBy[0]', $column);
          session()->put('CommandOrderBy[5]', session()->get('CommandOrderBy[3]'));
          session()->put('CommandOrderBy[3]', session()->get('CommandOrderBy[1]'));
          session()->put('CommandOrderBy[1]', 'asc');
        }
        return redirect( $_SERVER['HTTP_REFERER'] );
    }

    public function create(TaskRepository $taskRepo)
    {
       $tasks = $taskRepo->getAllSorted();
       return view('command.create')
            ->nest('taskSelectField', 'task.selectField', ["tasks"=>$tasks, "selectedTask"=>0]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
          'task_id' => 'required',
          'number' => 'required|integer|min:1|max:20',
          'command' => 'required|max:25',
          'description' => 'max:65',
          'points' => 'required|min:1',
        ]);

        $command = new Command;
        $command->task_id = $request->task_id;
        $command->number = $request->number;
        $command->command = $request->command;
        $command->description = $request->description;
        $command->points = $request->points;
        $command->save();

        return redirect($request->history_view);
    }
/*
    public function show(Command $polecenie, CommandRepository $commandRepo)
    {
        $previous = $commandRepo->previousRecordId($polecenie->id);
        $next = $commandRepo->nextRecordId($polecenie->id);
        $commandRatings = CommandRating::all() -> where('command_id', $polecenie->id);
        return view('command.show', ["command"=>$polecenie, "commandRatings"=>$commandRatings, "previous"=>$previous, "next"=>$next]);
    }
*/
    public function edit(Command $polecenie, TaskRepository $taskRepo)
    {
        $tasks = $taskRepo->getAllSorted();
        return view('command.edit', ["command"=>$polecenie])
             ->nest('taskSelectField', 'task.selectField', ["tasks"=>$tasks, "selectedTask"=>$polecenie->task_id]);
    }

    public function update(Request $request, Command $polecenie)
    {
        $this->validate($request, [
          'task_id' => 'required',
          'number' => 'required|integer|min:1|max:20',
          'command' => 'required|max:25',
          'description' => 'max:65',
          'points' => 'required|min:1',
        ]);

        $polecenie->task_id = $request->task_id;
        $polecenie->number = $request->number;
        $polecenie->command = $request->command;
        $polecenie->description = $request->description;
        $polecenie->points = $request->points;
        $polecenie->save();

        return redirect($request->history_view);
    }

    public function destroy(Command $polecenie)
    {
        $polecenie->delete();
        return redirect( $_SERVER['HTTP_REFERER'] );
    }
}
