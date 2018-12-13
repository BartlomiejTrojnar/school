<?php
namespace App\Http\Controllers;
use App\Models\Task;
use App\Repositories\TaskRepository;

//use App\Models\Command;
//use App\Models\TaskRating;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(TaskRepository $taskRepo)
    {
        $tasks = $taskRepo->getAllSorted();
        return view('task.index', ["tasks"=>$tasks]);
    }

    public function orderBy($column)
    {
        if(session()->get('TaskOrderBy[0]') == $column)
          if(session()->get('TaskOrderBy[1]') == 'desc')
            session()->put('TaskOrderBy[1]', 'asc');
          else
            session()->put('TaskOrderBy[1]', 'desc');
        else
        {
          session()->put('TaskOrderBy[4]', session()->get('TaskOrderBy[2]'));
          session()->put('TaskOrderBy[2]', session()->get('TaskOrderBy[0]'));
          session()->put('TaskOrderBy[0]', $column);
          session()->put('TaskOrderBy[5]', session()->get('TaskOrderBy[3]'));
          session()->put('TaskOrderBy[3]', session()->get('TaskOrderBy[1]'));
          session()->put('TaskOrderBy[1]', 'asc');
        }
        return redirect( $_SERVER['HTTP_REFERER'] );
    }

    public function create()
    {
        return view('task.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
          'name' => 'required|max:150',
          'points' => 'required|integer|max:100',
          'importance' => 'required|numeric',
          'sheet_name' => 'max:20',
        ]);

        $task = new Task;
        $task->name = $request->name;
        $task->points = $request->points;
        $task->importance = $request->importance;
        $task->sheet_name = $request->sheet_name;
        $task->save();

        return redirect($request->history_view);
    }

    public function show($id, $view='', TaskRepository $taskRepo)
    {
        if(empty(session()->get('taskView')))  session()->put('taskView', 'showInfo');
        if($view)  session()->put('taskView', $view);
        $task = $taskRepo -> find($id);
        $previous = $taskRepo -> PreviousRecordId($id);
        $next = $taskRepo -> NextRecordId($id);

        switch(session()->get('taskView')) {
          case 'showInfo':
              return view('task.show', ["task"=>$task, "previous"=>$previous, "next"=>$next])
                  -> nest('subView', 'task.showInfo', ["task"=>$task]);
              exit;
          break;
          case 'showCommands':
              $subTitle = "Polecenia w zadaniu";
              $commands = $task -> commands()
                  -> orderBy( session()->get('CommandOrderBy[0]'), session()->get('CommandOrderBy[1]') )
                  -> orderBy( session()->get('CommandOrderBy[2]'), session()->get('CommandOrderBy[3]') )
                  -> orderBy( session()->get('CommandOrderBy[4]'), session()->get('CommandOrderBy[4]') )
                  -> get();
              return view('task.show', ["task"=>$task, "previous"=>$previous, "next"=>$next])
                  -> nest('subView', 'command.table', ["task"=>$task, "subTitle"=>$subTitle, "commands"=>$commands]);
              exit;
          break;
/*
             case 'showRatings':
               $taskRatings = TaskRating::all()->where('task_id', $id);
               return view('task.showRatings', ["task"=>$task, "taskRatings"=>$taskRatings, "previous"=>$previous, "next"=>$next]);
             break;
*/
          default:
              printf('<p style="background: #bb0; color: #f00; font-size: x-large; text-align: center; border: 3px solid red; padding: 5px;">Widok %s nieznany</p>', $view);
              exit;
          break;
        }
    }

    public function edit(Task $zadanie)
    {
        return view('task.edit', ["task"=>$zadanie]);
    }

    public function update(Request $request, Task $zadanie)
    {
        $this->validate($request, [
          'name' => 'required|max:150',
          'points' => 'required|integer|max:100',
          'importance' => 'required|numeric',
          'sheet_name' => 'max:20',
        ]);

        $zadanie->name = $request->name;
        $zadanie->points = $request->points;
        $zadanie->importance = $request->importance;
        $zadanie->sheet_name = $request->sheet_name;
        $zadanie->save();

        return redirect($request->history_view);
    }

    public function destroy(Task $zadanie)
    {
        $zadanie->delete();
        return redirect( $_SERVER['HTTP_REFERER'] );
    }
}
