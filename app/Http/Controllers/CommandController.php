<?php
// ------------------------ (C) mgr inż. Bartłomiej Trojnar; 08.12.2021 ------------------------ //
namespace App\Http\Controllers;
use App\Models\Command;
use App\Repositories\CommandRepository;

use App\Repositories\TaskRepository;
use Illuminate\Http\Request;

class CommandController extends Controller {
    public function create() {
        $task = session()->get('taskSelected');
        return view('command.create', ["task"=>$task]);
    }

    public function store(Request $request) {
        return 2;
        $this -> validate($request, [
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
        $command -> save();

        return $command->id;
    }

    public function refreshRow(Request $request, Command $command) {
        $command = $command -> find($request->id);
        // $command = $command->where('id', '=', $request->id);
        return $command->id;
        return view('command.row', ["command"=>$command, "lp"=>$request->lp]);
    }


    /*
    public function index(CommandRepository $commandRepo)
    {
        $commands = $commandRepo->getAllSorted();
        return view('command.index')
            -> nest('commandTable', 'command.table', ["commands"=>$commands, "subTitle"=>""]);
    }
*/
/*
    public function orderBy($column) {
        if(session()->get('CommandOrderBy[0]') == $column)
            if(session()->get('CommandOrderBy[1]') == 'desc')  session()->put('CommandOrderBy[1]', 'asc');
            else  session()->put('CommandOrderBy[1]', 'desc');
        else {
            session()->put('CommandOrderBy[4]', session()->get('CommandOrderBy[2]'));
            session()->put('CommandOrderBy[2]', session()->get('CommandOrderBy[0]'));
            session()->put('CommandOrderBy[0]', $column);
            session()->put('CommandOrderBy[5]', session()->get('CommandOrderBy[3]'));
            session()->put('CommandOrderBy[3]', session()->get('CommandOrderBy[1]'));
            session()->put('CommandOrderBy[1]', 'asc');
        }
        return redirect( $_SERVER['HTTP_REFERER'] );
    }

    public function show($id, CommandRepository $commandRepo, $view='') {
        if( empty(session() -> get('commandView')) )  session() -> put('commandView', 'showInfo');
        if($view)  session() -> put('commandView', $view);
        session() -> put('commandSelected', $id);
        $command = $commandRepo -> find($id);
        $commands = $commandRepo -> getTaskCommands( session()->get('taskSelected') );
        list($this->previous, $this->next) = $commandRepo -> nextAndPreviousRecordId($commands, $id);

        switch(session()->get('commandView')) {
            case 'showInfo':
                return view('command.show', ["command"=>$command, "previous"=>$this->previous, "next"=>$this->next])
                    -> nest('subView', 'command.showInfo', ["command"=>$command]);
            default:
                printf('<p style="background: #bb0; color: #f00; font-size: x-large; text-align: center; border: 3px solid red; padding: 5px;">Widok %s nieznany</p>', session()->get('commandView'));
        }
    }

    public function edit(Request $request, Command $command, TaskRepository $taskRepo) {
        $command = $command -> find($request->id);
        return view('command.edit', ["command"=>$command]);
    }

    public function update($id, Request $request, Command $command) {
        $command = $command -> find($id);
        $this -> validate($request, [
          'task_id' => 'required',
          'number' => 'required|integer|min:1|max:20',
          'command' => 'required|max:25',
          'description' => 'max:65',
          'points' => 'required|min:1',
        ]);

        $command->task_id = $request->task_id;
        $command->number = $request->number;
        $command->command = $request->command;
        $command->description = $request->description;
        $command->points = $request->points;
        $command -> save();

        return $command->id;
    }

    public function destroy($id, Command $command) {
        $command = $command -> find($id);
        $command -> delete();
        return 1;
    }

    /*
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
*/
}
