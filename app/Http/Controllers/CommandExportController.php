<?php
// ----------------------- (C) mgr inż. Bartłomiej Trojnar; (II) listopad 2020 ----------------------- //

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Maatwebsite\Excel\Facades\Excel;

use App\Models\Command;
use App\Repositories\CommandRepository;
use App\Repositories\TaskRepository;

class CommandExportController extends Controller  {
    public function taskCommandExport(Request $request, TaskRepository $taskRepo, CommandRepository $commandRepo)  {
        $this->task = $taskRepo -> find($request->id);
        $commands = $commandRepo -> getTaskCommands($this->task->id);

        Excel::create('zadania', function($excel) use ($commands) {
            // Set the title
            $excel->setTitle('polecenia w zadaniu');
            // Chain the setters
            $excel->setCreator('Group Manager by Bartłomiej Trojnar')
                  ->setCompany('II Liceum Ogólnokształcące w Łańcucie');
            // Call them separately
            $excel->setDescription('Wygenerowano za pomocą aplikacji Group Manager');

            $this -> commandsWrite($excel, $commands);
        })->export('xlsx');
    }

    private function commandsWrite($excel, $commands) {
        $excel->sheet($this->task->name, function($sheet) use ($commands) {
            $sheet->loadView('command.taskCommandsExport', ["task"=>$this->task, "commands"=>$commands]);
            $sheet->getStyle("J1:P1")->getAlignment()->setTextRotation(90);
            $countCommands = count($commands);
            for($col=16; $col<=16+$countCommands; $col++)
                $sheet->getCellByColumnAndRow($col,1)->getStyle()->getAlignment()->setTextRotation(90);
                //$sheet->getColumn($col)->setWidth(25);
        });
    }


    // ----------------------------------- importowanie poleceń ----------------------------------- //
    public function taskCommandImport(Request $request, TaskRepository $taskRepo)  {
        $this->task = $taskRepo -> find($request->id);
        $this->forms="";

        Excel::selectSheets($this->task->sheet_name)->load('C:\dane\oceny\oceny2021.xlsx', function($reader) {
            $cos = $reader->first();
            $import = false;
            $this->number=0;
    
            foreach($cos as $key=>$value) {
                if($key=='0') break;
                if($import) {
                    // sprawdzenie czy jest już takie polecenie w bazie danych
                    $commands = Command::where('task_id', $this->task->id) -> where('command', $key) -> get();
                    $countCommands = 0;
                    foreach($commands as $command) $countCommands++;

                    // jeżeli jest więcej niż 1 takie polecenie - BŁĄD
                    if($countCommands>1) {
                        $this->forms .= '<p>Polecenie występuje więcej niż raz!!!</p>';
                        continue;
                    }
                    // jeżeli jest dokładnie 1 takie polecenie - sprawdzenie czy jest takie samo
                    if($countCommands==1) {
                        $zmien=false;
                        if($commands[0]->number != ++$this->number) $zmien=true;
                        if($commands[0]->points != $value) $zmien=true;
                        if($zmien) {
                            $command = $command -> find($commands[0]->id);
                            $newCommand = $command -> find($commands[0]->id);
                            $newCommand->number = $this->number;
                            $newCommand->command = $key;
                            $newCommand->description = $command->description;
                            $newCommand->points = $value;
                            $this->forms .= view('command.editForm', ["command"=>$command, "newCommand"=>$newCommand, "number"=>$this->number]);
                        }
                        continue;
                    }

                    // jeżeli polecenia nie ma - wprowadzenie polecenia do bazy danych
                    $command = new Command;
                    $command->task_id = $this->task->id;
                    $command->number = ++$this->number;
                    $command->command = $key;
                    $command->points = $value;
                    $this->forms .= view('command.addForm', ["command"=>$command, "number"=>$this->number]);
                }
                if($key == 'data_dziennika') $import=true;
            }
        });

        return view('command.import', ["forms"=>$this->forms, "countImportCommands"=>$this->number]);
    }

    public function storeFromImport(Request $request, Command $command) {
        $countImportCommands = $_POST['countImportCommands'];
        for($i=1; $i<=$countImportCommands; $i++) {
            if(isset($_POST['add_'.$i]))     $this->storeCommandFromImport($i, $command);
            if(isset($_POST['discard_'.$i])) printf('<p>Opuszczam numer %s.</p>', $i);
            if(isset($_POST['edit_'.$i]))    $this->updateCommandFromImport($i, $command);
        }
        return redirect($request->history_view);
    }

    private function storeCommandFromImport($i, $command) {
        $command = new Command;
        $command->task_id = $_POST['task_id_'.$i];
        $command->number = $_POST['number_'.$i];
        $command->command = $_POST['command_'.$i];
        $command->description = $_POST['description_'.$i];
        $command->points = $_POST['points_'.$i];
        $command -> save();
        return;
    }

    private function updateCommandFromImport($i, $command) {
        $id = $_POST['id_'.$i];
        $command = $command -> find($id);
        $command->number = $_POST['number_'.$i];
        $command->command = $_POST['command_'.$i];
        $command->description = $_POST['description_'.$i];
        $command->points = $_POST['points_'.$i];
        $command -> save();
        return;
    }
}
