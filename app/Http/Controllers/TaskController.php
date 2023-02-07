<?php
// ------------------------ (C) mgr inż. Bartłomiej Trojnar; 08.12.2021 ------------------------ //
namespace App\Http\Controllers;
use App\Models\Task;
use App\Repositories\TaskRepository;

use App\Models\Grade;
use App\Models\Group;
use App\Models\TaskRating;
use App\Repositories\CommandRepository;
use App\Repositories\GradeRepository;
use App\Repositories\GroupRepository;
use App\Repositories\TaskRatingRepository;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(TaskRepository $taskRepo) {
        $tasks = $taskRepo -> getAllSorted();
        return view('task.index', ["tasks"=>$tasks]);
    }

    public function orderBy($column) {
        if(session()->get('TaskOrderBy[0]') == $column)
            if(session()->get('TaskOrderBy[1]') == 'desc')  session()->put('TaskOrderBy[1]', 'asc');
            else  session()->put('TaskOrderBy[1]', 'desc');
        else {
            session()->put('TaskOrderBy[4]', session()->get('TaskOrderBy[2]'));
            session()->put('TaskOrderBy[2]', session()->get('TaskOrderBy[0]'));
            session()->put('TaskOrderBy[0]', $column);
            session()->put('TaskOrderBy[5]', session()->get('TaskOrderBy[3]'));
            session()->put('TaskOrderBy[3]', session()->get('TaskOrderBy[1]'));
            session()->put('TaskOrderBy[1]', 'asc');
        }
        return redirect( $_SERVER['HTTP_REFERER'] );
    }

    public function create() {
        return view('task.create');
    }

    public function store(Request $request) {
        $this -> validate($request, [
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
        $task -> save();

        return $task->id;
    }

    public function show($id, TaskRepository $taskRepo, CommandRepository $commandRepo, TaskRatingRepository $taskRatingRepo, GradeRepository $gradeRepo, GroupRepository $groupRepo, $view='') {
        session() -> put('taskView', 'info');
        if( empty(session() -> get('taskView')) )  session() -> put('taskView', 'info');
        if($view)  session() -> put('taskView', $view);
        session() -> put('taskSelected', $id);
        $this->task = $taskRepo -> find($id);

        $tasks = $taskRepo -> getAllSorted();
        list($this->previous, $this->next) = $taskRepo -> nextAndPreviousRecordId($tasks, $id);

        switch(session()->get('taskView')) {
            case 'info':    return $this -> showInfo($commandRepo);
            case 'oceny':   return $this -> showRatings($taskRatingRepo, $gradeRepo, $groupRepo);
            default:
                printf('<p style="background: #bb0; color: #f00; font-size: x-large; text-align: center; border: 3px solid red; padding: 5px;">Widok %s nieznany</p>', session()->get('taskView'));
            break;
        }
    }

    private function showInfo($commandRepo) {
        $commands = $commandRepo -> getTaskCommands($this->task->id);
        $commandTable = view('command.table', ["task"=>$this->task, "commands"=>$commands]);
        return view('task.show', ["task"=>$this->task, "previous"=>$this->previous, "next"=>$this->next, "subView"=>$commandTable, "css"=>'', "js"=>'']);
    }

    private function showRatings($taskRatingRepo, $gradeRepo, $groupRepo) {
        $grades = $gradeRepo -> getAllSorted();
        $gradeSelected = session()->get('gradeSelected');
        $gradeSF = view('grade.selectField', ["grades"=>$grades, "gradeSelected"=>$gradeSelected, "name"=>"grade_id"]);
        $dateView = session() -> get('dateView');
        $groups = $groupRepo -> getGroups($dateView, $dateView, $gradeSelected);
        $groupSelected = session()->get('groupSelected');
        $groupSF = view('group.selectField', ["groups"=>$groups, "groupSelected"=>$groupSelected, "name"=>"group_id"]);
        $diaryYesNoSelected = session() -> get('diaryYesNoSelected');
        $diarySF = view('layouts.yesNoSelectField', ["fieldName"=>"diaryYesNo", "valueSelected"=>$diaryYesNoSelected]);
        $taskRatings = $taskRatingRepo -> getTaskRatings($this->task->id, 0, $gradeSelected, $groupSelected, $diaryYesNoSelected);
        $taskRatingsTable = view('taskRating.table', ["taskRatings"=>$taskRatings, "gradeSF"=>$gradeSF, "groupSF"=>$groupSF, "diarySF"=>$diarySF, "task"=>$this->task]);

        $js = "taskRating/forTask.js";
        $css = "taskRating/style.css";
        return view('task.show', ["task"=>$this->task, "previous"=>$this->previous, "next"=>$this->next, "subView"=>$taskRatingsTable, "js"=>$js, "css"=>$css]);
    }

    public function edit(Request $request, Task $task) {
        $task = $task -> find($request->id);
        return view('task.edit', ["task"=>$task]);
    }

    public function update($id, Request $request, Task $task) {
        $task = $task -> find($id);
        $this -> validate($request, [
          'name' => 'required|max:150',
          'points' => 'required|integer|max:100',
          'importance' => 'required|numeric',
          'sheet_name' => 'max:20',
        ]);

        $task->name = $request->name;
        $task->points = $request->points;
        $task->importance = $request->importance;
        $task->sheet_name = $request->sheet_name;
        $task -> save();

        return $task->id;
    }

    public function destroy($id, Task $task) {
        $task = $task -> find($id);
        $task -> delete();
        return 1;
    }

    public function refreshRow(Request $request, Task $task) {
        $task = $task -> find($request->id);
        return view('task.row', ["task"=>$task, "lp"=>$request->lp]);
    }
}