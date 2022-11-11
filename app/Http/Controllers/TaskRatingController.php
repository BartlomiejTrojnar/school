<?php
// -------------------- (C) mgr inż. Bartłomiej Trojnar; (II) kwiecień 2021 -------------------- //
namespace App\Http\Controllers;
use App\Models\TaskRating;
use App\Repositories\TaskRatingRepository;

use App\Repositories\StudentRepository;
use App\Repositories\TaskRepository;
use Illuminate\Http\Request;

class TaskRatingController extends Controller
{
    public function orderBy($column) {
        if(session()->get('TaskRatingOrderBy[0]') == $column)
            if(session()->get('TaskRatingOrderBy[1]') == 'desc')  session()->put('TaskRatingOrderBy[1]', 'asc');
            else  session()->put('TaskRatingOrderBy[1]', 'desc');
        else {
            session()->put('TaskRatingOrderBy[4]', session()->get('TaskRatingOrderBy[2]'));
            session()->put('TaskRatingOrderBy[2]', session()->get('TaskRatingOrderBy[0]'));
            session()->put('TaskRatingOrderBy[0]', $column);
            session()->put('TaskRatingOrderBy[5]', session()->get('TaskRatingOrderBy[3]'));
            session()->put('TaskRatingOrderBy[3]', session()->get('TaskRatingOrderBy[1]'));
            session()->put('TaskRatingOrderBy[1]', 'asc');
        }
        return redirect( $_SERVER['HTTP_REFERER'] );
    }

    public function create(Request $request, TaskRepository $taskRepo) {
        if($request->version == "forStudent")   return $this->createRowForStudent($request->student_id, $taskRepo);
        return $request->version;
    }

    private function createRowForStudent($student_id, $taskRepo) {
        $tasks = $taskRepo -> getAllSorted();
        $taskSelected = session() -> get('taskSelected');
        $taskSelectField = view('task.selectField', ["tasks"=>$tasks, "taskSelected"=>$taskSelected]);
        $task = $taskRepo -> find($taskSelected);

        return view('taskRating.createRowForStudent', ["student_id"=>$student_id, "taskSelectField"=>$taskSelectField, "task"=>$task]);
    }

    // metoda tworząca kopię oceny zadania oraz wyświetlająca formularz umożliwiający modyfikację tej kopii i oryginału (poprawianie oceny zadania)
    public function improvement($id, TaskRatingRepository $taskRatingRepo) {
        $studentSelected = session() -> get('studentSelected');
        $taskSelected = session() -> get('taskSelected');
                
        // klonowanie oceny zadania
        $taskRating = $taskRatingRepo -> find($id);
        $newTaskRating = new TaskRating;
        $newTaskRating->student_id = $taskRating->student_id;
        $newTaskRating->task_id = $taskRating->task_id;
        $newTaskRating->deadline = date('Y-m-d');
        $newTaskRating->implementation_date = $taskRating->implementation_date;
        $newTaskRating->version = $taskRating->version+1;
            // obliczenie propozycji wagi dla poprawionego zadania
            $timeEnd = mktime(0, 0, 0, substr($newTaskRating->deadline,5,2), substr($newTaskRating->deadline,8,2), substr($newTaskRating->deadline,0,4));
            $timeStart = mktime(0, 0, 0, substr($taskRating->entry_date,5,2), substr($taskRating->entry_date,8,2), substr($taskRating->entry_date,0,4));
            $liczba = ceil( ($timeEnd-$timeStart) / 86400);
            if($liczba < 14)    $newTaskRating->importance = $taskRating->importance*0.9;
            if($liczba > 100) $liczba = 100;
        $newTaskRating->importance = $taskRating->importance - $liczba*0.005;
        $newTaskRating->rating_date = NULL;
        $newTaskRating->points = NULL;
        $newTaskRating->rating = NULL;
        $newTaskRating->comments = NULL;
        $newTaskRating->diary = 0;
        $newTaskRating->entry_date = NULL;
        $newTaskRating -> save();
        // poprawienie wagi w oryginalnym zadaniu
        $taskRating->importance = $taskRating->importance - $newTaskRating->importance;
        $taskRating -> save();

        // wyświetlenie formularza umożliwiającego zmianę
        $taskRatings = $taskRatingRepo -> getTaskRatings($taskSelected, $studentSelected);
        return view('taskRating.improvement', ["taskRatings"=>$taskRatings]);
    }

    // metoda tworząca ocenę zadania dla wielu uczniów jednocześnie
    public function createLot(TaskRatingRepository $taskRatingRepo, TaskRepository $taskRepo, StudentRepository $studentRepo) {
        $task = $taskRepo -> find(session()->get('taskSelected'));
        $dateView = session()->get('dateView');
        $gradeSelected = session()->get('gradeSelected');
        $groupSelected = session()->get('groupSelected');

        if($gradeSelected) {
            $grades[] = $gradeSelected;
            $students = $studentRepo -> getStudentsFromGrades( $grades, $dateView );
        }
        elseif($groupSelected)
            $students = $studentRepo -> getStudentsFromGroup( $groupSelected, $dateView );
        else {
            print_r("Niewybrana klasa lub grupa");
            return;
        }
        $i=0;
        foreach($students as $student)
            $students[$i++]['countTaskRatings'] = $taskRatingRepo -> countTaskRatingForStudent($task->id, $student->id);
        return view('taskRating.createLot', ["task"=>$task, "students"=>$students]);
    }

    public function store(Request $request) {
        $this -> validate($request, [
          'student_id' => 'required',
          'task_id' => 'required',
          'deadline' => 'date',
          'implementation_date' => 'date',
          'version' => 'required|integer|between:1,10',
          'importance' => 'required|numeric',
          'points' => 'numeric',
          'rating' => 'max:2',
          'comments' => 'max:50',
        ]);

        $taskRating = new TaskRating;
        $taskRating->student_id = $request->student_id;
        $taskRating->task_id    = $request->task_id;
        $taskRating->deadline   = $request->deadline;
        $taskRating->implementation_date = $request->implementation_date;
        $taskRating->version    = $request->version;
        $taskRating->importance = $request->importance;
        $taskRating->rating_date= $request->rating_date;
        $taskRating->points     = $request->points;
        $taskRating->rating     = $request->rating;
        $taskRating->comments   = $request->comments;
        if($request->diary=="on") $taskRating->diary = true; else $taskRating->diary = false;
        $taskRating->entry_date = $request->entry_date;
        $taskRating -> save();

        return $taskRating->id;
    }

    public function storeLot(Request $request) {
        foreach($_POST as $key=>$value)
            if(substr($key, 0, 7) == 'student') {
                $taskRating = new TaskRating;
                $taskRating->task_id = $request->task_id;
                $taskRating->deadline = $request->deadline;
                $taskRating->importance = $request->importance;

                $taskRating->implementation_date = NULL;
                $taskRating->version = 1;
                $taskRating->rating_date = NULL;
                $taskRating->points = NULL;
                $taskRating->rating = NULL;
                $taskRating->comments = NULL;
                $taskRating->diary = false;
                $taskRating->entry_date = NULL;

                $taskRating->student_id = $value;
                $taskRating -> save();
            }
        return redirect( $request->history_view );
    }

    public function diaryYesNoChange($value) { session() -> put('diaryYesNoSelected', $value); }

    public function show($id, TaskRating $taskRating, TaskRatingRepository $taskRatingRepo) {
        $taskRating = $taskRatingRepo -> find($id);
        $previous = $taskRatingRepo->previousRecordId($id);
        $next = $taskRatingRepo->nextRecordId($id);
        return view('taskRating.show', ["taskRating"=>$taskRating, "previous"=>$previous, "next"=>$next]);
    }

    public function edit($id, TaskRatingRepository $taskRatingRepo, TaskRepository $taskRepo, StudentRepository $studentRepo) {
        $taskRating = $taskRatingRepo -> find($id);
        $tasks = $taskRepo->getAllSorted();
        $taskSelected = $taskRating->task_id;
        $taskSF = view('task.selectField', ["tasks"=>$tasks, "taskSelected"=>$taskSelected]);

        //$students = $studentRepo->getAllSorted();
        //$studentSelected = $taskRating->student_id;
        //$studentSF = view('student.selectField', ["students"=>$students, "studentSelected"=>$studentSelected]);

        return view('taskRating.edit', ["taskRating"=>$taskRating, "taskSF"=>$taskSF]);
    }

    public function editLotTaskRatings(TaskRatingRepository $taskRatingRepo, TaskRepository $taskRepo) {
        $task = $taskRepo -> find(session()->get('taskSelected'));
        $gradeSelected = session()->get('gradeSelected');
        $diaryYesNoSelected = session() -> get('diaryYesNoSelected');

        $taskRatings = $taskRatingRepo -> getTaskRatings($task->id, $gradeSelected, $diaryYesNoSelected);
       
        return view('taskRating.editLotTaskRatings', ["taskRatings"=>$taskRatings]);
    }

    public function editLotTerms(TaskRatingRepository $taskRatingRepo, TaskRepository $taskRepo) {
        $task = $taskRepo -> find(session()->get('taskSelected'));
        $taskRatings = $taskRatingRepo -> getTaskRatings($task->id);
        return view('taskRating.editLotTerms', ["task"=>$task, "taskRatings"=>$taskRatings]);
    }

    public function editLotImplementationDates(TaskRatingRepository $taskRatingRepo, TaskRepository $taskRepo) {
        $task = $taskRepo -> find(session()->get('taskSelected'));
        $taskRatings = $taskRatingRepo -> getTaskRatings($task->id);
        return view('taskRating.editLotImplementationDates', ["task"=>$task, "taskRatings"=>$taskRatings]);
    }

    public function editLotImportances(TaskRatingRepository $taskRatingRepo, TaskRepository $taskRepo) {
        $task = $taskRepo -> find(session()->get('taskSelected'));
        $taskRatings = $taskRatingRepo -> getTaskRatings($task->id);
        return view('taskRating.editLotImportances', ["task"=>$task, "taskRatings"=>$taskRatings]);
    }

    public function editLotPoints(TaskRatingRepository $taskRatingRepo, TaskRepository $taskRepo) {
        $task = $taskRepo -> find(session()->get('taskSelected'));
        $taskRatings = $taskRatingRepo -> getTaskRatings($task->id);
        return view('taskRating.editLotPoints', ["task"=>$task, "taskRatings"=>$taskRatings]);
    }

    public function editLotRatings(TaskRatingRepository $taskRatingRepo, TaskRepository $taskRepo) {
        $task = $taskRepo -> find(session()->get('taskSelected'));
        $taskRatings = $taskRatingRepo -> getTaskRatings($task->id);
        return view('taskRating.editLotRatings', ["task"=>$task, "taskRatings"=>$taskRatings]);
    }

    public function editLotRatingDates(TaskRatingRepository $taskRatingRepo, TaskRepository $taskRepo) {
        $task = $taskRepo -> find(session()->get('taskSelected'));
        $gradeSelected = session()->get('gradeSelected');
        $groupSelected = session()->get('groupSelected');
        $diaryYesNoSelected = session() -> get('diaryYesNoSelected');

        $taskRatings = $taskRatingRepo -> getTaskRatings($task->id, 0, $gradeSelected, $groupSelected, $diaryYesNoSelected);
        return view('taskRating.editLotRatingDates', ["task"=>$task, "taskRatings"=>$taskRatings]);
    }

    public function editStudentRatings(TaskRatingRepository $taskRatingRepo, TaskRepository $taskRepo) {
        $student_id = session()->get('studentSelected');
        $taskRatings = $taskRatingRepo -> getStudentTaskRatings($student_id);
        return view('taskRating.editLotTaskRatings', ["taskRatings"=>$taskRatings]);
    }

    public function update($id, Request $request, TaskRating $taskRating) {
        $taskRating = $taskRating -> find($id);
        $this -> validate($request, [
          'student_id' => 'required',
          'task_id' => 'required',
          'version' => 'required|integer|between:1,6',
          'importance' => 'required|numeric',
          'points' => 'numeric',
          'rating' => 'max:2',
          'comments' => 'max:50',
        ]);

        $taskRating->student_id = $request->student_id;
        $taskRating->task_id = $request->task_id;

        $taskRating->deadline = $request->deadline;
        if($request->deadline=="" || $request->deadline=="0000-00-00") $taskRating->deadline = null;
        $taskRating->implementation_date = $request->implementation_date;
        if($request->implementation_date=="" || $request->implementation_date=="0000-00-00") $taskRating->implementation_date = null;

        $taskRating->version = $request->version;
        $taskRating->importance = $request->importance;
        $taskRating->rating_date = $request->rating_date;
        if($request->rating_date=="" || $request->rating_date=="0000-00-00") $taskRating->rating_date = null;
        $taskRating->points = $request->points;
        $taskRating->rating = $request->rating;
        $taskRating->comments = $request->comments;
        if($request->diary=="on") $taskRating->diary = true; else $taskRating->diary = false;
        $taskRating->entry_date = $request->entry_date;
        if($request->entry_date=="" || $request->entry_date=="0000-00-00") $taskRating->entry_date = null;
        $taskRating -> save();

        return $taskRating->id;
    }

    public function updateLotTaskRatings(Request $request, TaskRating $taskRating) {
        foreach($request->request as $key => $value) {
            if(substr($key, 0, 14) == 'task_rating_id') {
                $id = $value;
                $taskRating = $taskRating -> find($id);
                if($request["deadline$id"]) $taskRating->deadline = $request["deadline$id"]; else $taskRating->deadline = NULL;
                if($request["implementation_date$id"]) $taskRating->implementation_date = $request["implementation_date$id"]; else $taskRating->implementation_date = NULL;
                $taskRating->version = $request["version$id"];
                $taskRating->importance = $request["importance$id"];
                if($request["rating_date$id"]) $taskRating->rating_date = $request["rating_date$id"]; else $taskRating->rating_date = NULL;
                $taskRating->points = $request["points$id"];
                $taskRating->rating = $request["rating$id"];
                $taskRating->comments = $request["comments$id"];
                if($request["diary$id"])  {
                    $taskRating->diary = 1;
                    $taskRating->entry_date = $request["entry_date$id"];
                }
                else  {
                    $taskRating->diary = 0;
                    $taskRating->entry_date = NULL;
                }
                $taskRating -> save();
            }
        }

        return redirect($request->historyView);
    }

    public function updateLotTerms(Request $request, TaskRating $taskRating) {
        foreach($request->request as $key => $value) {
            if(substr($key, 0, 8) == 'deadline') {
                $id = substr($key, 8);
                $taskRating = $taskRating -> find($id);
                $taskRating->deadline = $value;
                if(!$value) $taskRating->deadline = NULL;
                $taskRating -> save();
            }
        }

        return redirect($request->historyView);
    }

    public function updateLotImplementationDates(Request $request, TaskRating $taskRating) {
        foreach($request->request as $key => $value) {
            if(substr($key, 0, 14) == 'implementation') {
                $id = substr($key, 14);
                $taskRating = $taskRating -> find($id);
                $taskRating->implementation_date = $value;
                if(!$value) $taskRating->implementation_date = NULL;
                $taskRating -> save();
            }
        }

        return redirect($request->historyView);
    }

    public function updateLotImportances(Request $request, TaskRating $taskRating) {
        foreach($request->request as $key => $value) {
            if(substr($key, 0, 10) == 'importance') {
                $id = substr($key, 10);
                $taskRating = $taskRating -> find($id);
                $taskRating->importance = $value;
                if(!$value) $taskRating->importance = 0;
                $taskRating -> save();
            }
        }

        return redirect($request->historyView);
    }

    public function updateLotPoints(Request $request, TaskRating $taskRating) {
        foreach($request->request as $key => $value) {
            if(substr($key, 0, 6) == 'points') {
                $id = substr($key, 6);
                $taskRating = $taskRating -> find($id);
                $taskRating->points = $value;
                if($value=="") $taskRating->points = NULL;
                $taskRating -> save();
            }
        }

        return redirect($request->historyView);
    }

    public function updateLotRatings(Request $request, TaskRating $taskRating) {
        foreach($request->request as $key => $value) {
            if(substr($key, 0, 6) == 'rating') {
                $id = substr($key, 6);
                $taskRating = $taskRating -> find($id);
                $taskRating->rating = $value;
                if($value=="") $taskRating->rating = NULL;
                $taskRating->rating_date=date('Y-m-d H:i');
                $taskRating -> save();
            }
        }

        return redirect($request->historyView);
    }

    public function updateLotRatingDates(Request $request, TaskRating $taskRating) {
        foreach($request->request as $key => $value) {
            if(substr($key, 0, 11) == 'rating_date') {
                $id = substr($key, 11);
                $taskRating = $taskRating -> find($id);
                $taskRating->rating_date = $value;
                if($value=="") $taskRating->rating_date = NULL;
                $taskRating->rating_date=date('Y-m-d H:i');
                $taskRating -> save();
            }
        }

        return redirect($request->historyView);
    }

    public function writeInTheDiary(Request $request, TaskRating $taskRating) {
        $taskRating = $taskRating -> find($request->id);
        $taskRating->diary = 1;
        $taskRating->entry_date = date('Y-m-d h:m');
        $taskRating -> save();
        return $taskRating->entry_date;
    }

    public function removeFromDiary(Request $request, TaskRating $taskRating) {
        $taskRating = $taskRating -> find($request->id);
        $taskRating->diary = 0;
        $taskRating->entry_date = NULL;
        $taskRating -> save();
        return 1;
    }

    public function destroy($id, TaskRating $taskRating) {
        $taskRating = $taskRating -> find($id);
        $taskRating -> delete();
        return 1;
        //return redirect( $_SERVER['HTTP_REFERER'] );
    }

    public function refreshRow(Request $request, TaskRatingRepository $taskRatingRepo) {
        $taskRating = $taskRatingRepo -> find($request->id);
        return view('taskRating.row', ["taskRating"=>$taskRating, "lp"=>$request->lp]);

        return $request->version;
        //$student = $studentRepo -> find($request->student_id);
        //$declarations = $student -> declarations;
        //return view('declaration.tableForStudent', ["declarations"=>$declarations]);
    }

    public function refreshTable(Request $request, TaskRatingRepository $taskRatingRepo) {
        if($request->version == "forStudent")   return $this -> refreshTableForStudent($request->student_id, $taskRatingRepo);
        return $request->version;
        //$student = $studentRepo -> find($request->student_id);
        //$declarations = $student -> declarations;
        //return view('declaration.tableForStudent', ["declarations"=>$declarations]);
    }

    private function refreshTableForStudent($student_id, $taskRatingRepo) {
        $taskRatings = $taskRatingRepo -> getStudentTaskRatings($student_id);
        return view('taskRating.tableForStudent', ["student"=>$student_id, "subTitle"=>"zadania ucznia2", "taskRatings"=>$taskRatings]);
        //return view('student.show', ["student"=>$this->student, "css"=>$css, "js"=>$js, "previous"=>$this->previous, "next"=>$this->next, "subView"=>$taskRatingsTable]);

        //$student = $studentRepo -> find($request->student_id);
        //$declarations = $student -> declarations;
        //return view('declaration.tableForStudent', ["declarations"=>$declarations]);
    }
}