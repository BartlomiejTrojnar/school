<?php
namespace App\Http\Controllers;
use App\Models\TaskRating;
use App\Repositories\TaskRatingRepository;

use App\Repositories\TaskRepository;
use App\Repositories\StudentRepository;

use Illuminate\Http\Request;

class TaskRatingController extends Controller
{
/*
    public function index(TaskRatingRepository $taskRatingRepo)
    {
        $taskRatings = $taskRatingRepo -> getAllSorted();
        return view('taskRating.index')
            -> nest('taskRatingTable', 'taskRating.table', ["taskRatings"=>$taskRatings, "subTitle"=>""]);
    }
*/
    public function orderBy($column)
    {
        if(session()->get('TaskRatingOrderBy[0]') == $column)
          if(session()->get('TaskRatingOrderBy[1]') == 'desc')
            session()->put('TaskRatingOrderBy[1]', 'asc');
          else
            session()->put('TaskRatingOrderBy[1]', 'desc');
        else
        {
          session()->put('TaskRatingOrderBy[4]', session()->get('TaskRatingOrderBy[2]'));
          session()->put('TaskRatingOrderBy[2]', session()->get('TaskRatingOrderBy[0]'));
          session()->put('TaskRatingOrderBy[0]', $column);
          session()->put('TaskRatingOrderBy[5]', session()->get('TaskRatingOrderBy[3]'));
          session()->put('TaskRatingOrderBy[3]', session()->get('TaskRatingOrderBy[1]'));
          session()->put('TaskRatingOrderBy[1]', 'asc');
        }
        return redirect( $_SERVER['HTTP_REFERER'] );
    }

    public function create(TaskRepository $taskRepo, StudentRepository $studentRepo)
    {
        $tasks = $taskRepo->getAllSorted();
        $students = $studentRepo->getAllSorted();

        if(isset($_GET['task_id'])) $taskSelected = $_GET['task_id'];   else $taskSelected = 0;
        if(isset($_GET['student_id'])) $studentSelected = $_GET['student_id'];   else $studentSelected = 0;

        return view('taskRating.create')
             ->nest('taskSelectField', 'task.selectField', ["tasks"=>$tasks, "taskSelected"=>$taskSelected])
             ->nest('studentSelectField', 'student.selectField', ["students"=>$students, "studentSelected"=>$studentSelected]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
          'student_id' => 'required',
          'task_id' => 'required',
          'deadline' => 'required',
          'implementation_date' => 'nullable|date',
          'version' => 'required|integer|between:1,10',
          'importance' => 'required|numeric',
          'points' => 'nullable|numeric',
          'rating' => 'nullable|max:2',
          'comments' => 'nullable|max:50',
        ]);

        $taskRating = new TaskRating;
        $taskRating->student_id = $request->student_id;
        $taskRating->task_id = $request->task_id;
        $taskRating->deadline = $request->deadline;
        $taskRating->implementation_date = $request->implementation_date;
        $taskRating->version = $request->version;
        $taskRating->importance = $request->importance;
        $taskRating->rating_date = $request->rating_date;
        $taskRating->points = $request->points;
        $taskRating->rating = $request->rating;
        $taskRating->comments = $request->comments;
        if($request->diary=="on") $taskRating->diary = true; else $taskRating->diary = false;
        $taskRating->entry_date = $request->entry_date;
        $taskRating->save();

        return redirect( $request->history_view );
    }
/*
    public function show(TaskRating $ocena_zadania, TaskRatingRepository $taskRatingRepo)
    {
        $previous = $taskRatingRepo->previousRecordId($ocena_zadania->id);
        $next = $taskRatingRepo->nextRecordId($ocena_zadania->id);
        return view('taskRating.show', ["taskRating"=>$ocena_zadania, "previous"=>$previous, "next"=>$next]);
    }
*/
    public function edit($id, TaskRatingRepository $taskRatingRepo, TaskRepository $taskRepo, StudentRepository $studentRepo)
    {
        $taskRating = $taskRatingRepo -> find($id);
        $tasks = $taskRepo->getAllSorted();
        $students = $studentRepo->getAllSorted();

        $taskSelected = $taskRating->task_id;
        $studentSelected = $taskRating->student_id;

        return view('taskRating.edit', ["taskRating"=>$taskRating])
             ->nest('taskSelectField', 'task.selectField', ["tasks"=>$tasks, "taskSelected"=>$taskSelected])
             ->nest('studentSelectField', 'student.selectField', ["students"=>$students, "studentSelected"=>$studentSelected]);
    }

    public function update(Request $request, $id, TaskRatingRepository $taskRatingRepo)
    {
        $ocena_zadania = $taskRatingRepo -> find($id);
        $this->validate($request, [
          'student_id' => 'required',
          'task_id' => 'required',
          'deadline' => 'required',
          'implementation_date' => 'nullable|date',
          'version' => 'required|integer|between:1,10',
          'importance' => 'required|numeric',
          'points' => 'nullable|numeric',
          'rating' => 'nullable|max:2',
          'comments' => 'nullable|max:50',
        ]);

        $ocena_zadania->student_id = $request->student_id;
        $ocena_zadania->task_id = $request->task_id;
        $ocena_zadania->deadline = $request->deadline;
        $ocena_zadania->implementation_date = $request->implementation_date;
        $ocena_zadania->version = $request->version;
        $ocena_zadania->importance = $request->importance;
        $ocena_zadania->rating_date = $request->rating_date;
        $ocena_zadania->points = $request->points;
        $ocena_zadania->rating = $request->rating;
        $ocena_zadania->comments = $request->comments;
        if($request->diary=="on") $ocena_zadania->diary = true; else $ocena_zadania->diary = false;
        $ocena_zadania->entry_date = $request->entry_date;
        $ocena_zadania->save();

        return redirect($request->historyView);
    }

    public function destroy($id, TaskRatingRepository $taskRatingRepo)
    {
        $taskRatingRepo->delete($id);
        return redirect( $_SERVER['HTTP_REFERER'] );
    }
}
