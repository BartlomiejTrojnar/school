<?php
namespace App\Http\Controllers;
use App\Models\Command;
use App\Repositories\CommandRepository;

use App\Models\Task;
use App\Repositories\TaskRepository;
//use App\Models\CommandRating;
use Excel;
use Illuminate\Http\Request;

class CommandController extends Controller
{
    public function index(CommandRepository $commandRepo)
    {
        $commands = $commandRepo->getAllSorted();
        return view('command.index')
            -> nest('commandTable', 'command.table', ["commands"=>$commands, "subTitle"=>""]);
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
            ->nest('taskSelectField', 'task.selectField', ["tasks"=>$tasks, "taskSelected"=>0]);
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

    public function show($id, $view='', CommandRepository $commandRepo)
    {
        if(empty(session()->get('commandView')))  session()->put('commandView', 'showInfo');
        if($view)  session()->put('commandView', $view);
        $command = $commandRepo -> find($id);
        $previous = $commandRepo -> PreviousRecordId($id);
        $next = $commandRepo -> NextRecordId($id);

        switch( session()->get('commandView') ) {
          case 'showInfo':
              return view('command.show', ["command"=>$command, "previous"=>$previous, "next"=>$next])
                  -> nest('subView', 'command.showInfo', ["command"=>$command]);
          break;
          default:
              printf('<p style="background: #bb0; color: #f00; font-size: x-large; text-align: center; border: 3px solid red; padding: 5px;">Widok %s nieznany</p>', session()->get('commandView'));
          break;
        }
    }

    public function edit(Command $polecenie, TaskRepository $taskRepo)
    {
        $tasks = $taskRepo->getAllSorted();
        return view('command.edit', ["command"=>$polecenie])
             ->nest('taskSelectField', 'task.selectField', ["tasks"=>$tasks, "taskSelected"=>$polecenie->task_id]);
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

    public function export($task_id, CommandRepository $commandRepo)
    {
        $commands = $commandRepo -> getTaskCommands($task_id);
        Excel::create('oceny', function($excel) use ($commands) {
            $sheetName = $commands[0]->task->sheet_name;
            $excel->sheet($sheetName, function($sheet) use ($commands) {
                $subTitle = "Polecenia w zadaniu " . $commands[0]->task->name;
                $sheet->loadView('command.export', ["subTitle"=>$subTitle, "commands"=>$commands]);
            });
        })->download('xlsx');
        exit;
    }

    public function import($task_id)
    {
        $sheetName = Task::find($task_id)->sheet_name;
        $rows = Excel::selectSheets($sheetName)->load('oceny.xlsx') -> get();
        if(empty($rows[0])) {echo 'Brak arkusza'; exit;}

        $i = 0; $errorNumber=0;
        foreach($rows[0] as $key=>$value) {
            if($i++ < 15) continue;
            $command = Command::where('command', $key)->first();
            if(!empty($command['id'])) {
                $commandErrors[$errorNumber]['lp'] = $i;
                $commandErrors[$errorNumber]['command'] = $key;
                $commandErrors[$errorNumber]['error'] = 'W pliku '. $value .' punktów, w bazie '. $command->points .' punktów.';
                $commandErrors[$errorNumber]['button'] = 'Zmień';
                $errorNumber++;
            }
            else {
                $commandErrors[$errorNumber]['lp'] = $i;
                $commandErrors[$errorNumber]['command'] = $key;
                $commandErrors[$errorNumber]['error'] = 'Brak polecenia w zadaniu.';
                $commandErrors[$errorNumber]['button'] = 'Wstaw';
                $errorNumber++;
            }
        }
        return view('command.import', ["commandErrors"=>$commandErrors]);
    }
}
