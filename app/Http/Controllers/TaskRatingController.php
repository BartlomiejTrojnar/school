<?php
namespace App\Http\Controllers;
//use App\Models\TaskRating;
//use App\Repositories\TaskRatingRepository;
//use App\Repositories\TaskRepository;
//use App\Repositories\StudentRepository;
use Illuminate\Http\Request;

class TaskRatingController extends Controller
{
/*
    public function index(TaskRatingRepository $taskRatingRepo)
    {
        for($i=0; $i<6; $i++)
          $orderBy[$i] = session()->get("TaskRatingOrderBy[$i]");

        $taskRatings = $taskRatingRepo->getAll($orderBy);
        return view('taskRating.index', ["taskRatings"=>$taskRatings]);
    }

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
        return redirect( route('ocena_zadania.index') );
    }

    public function create(TaskRepository $taskRepo, StudentRepository $studentRepo)
    {
        $tasks = $taskRepo->getAll();
        $students = $studentRepo->getAll();
        return view('taskRating.create')
             ->nest('taskSelectField', 'task.selectField', ["tasks"=>$tasks, "selectedTask"=>0])
             ->nest('studentSelectField', 'student.selectField', ["students"=>$students, "selectedStudent"=>0]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
          'student_id' => 'required',
          'task_id' => 'required',
          'deadline' => 'required',
          'implementation_date' => 'date',
          'version' => 'required|integer|between:1,10',
          'importance' => 'required|numeric',
          'points' => 'nullable|numeric',
          'rating' => 'max:2',
          'comments' => 'max:50',
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

        return redirect($request->history_view);
    }

    public function show(TaskRating $ocena_zadania, TaskRatingRepository $taskRatingRepo)
    {
        $previous = $taskRatingRepo->previousRecordId($ocena_zadania->id);
        $next = $taskRatingRepo->nextRecordId($ocena_zadania->id);
        return view('taskRating.show', ["taskRating"=>$ocena_zadania, "previous"=>$previous, "next"=>$next]);
    }

    public function edit($id, TaskRatingRepository $taskRatingRepo, TaskRepository $taskRepo, StudentRepository $studentRepo)
    {
        $ocena_zadania = $taskRatingRepo -> find($id);
        $tasks = $taskRepo->getAll();
        $students = $studentRepo->getAll();
        return view('taskRating.edit', ["taskRating"=>$ocena_zadania])
             ->nest('taskSelectField', 'task.selectField', ["tasks"=>$tasks, "selectedTask"=>$ocena_zadania->task_id])
             ->nest('studentSelectField', 'student.selectField', ["students"=>$students, "selectedStudent"=>$ocena_zadania->student_id]);
    }

    public function update(Request $request, $id, TaskRatingRepository $taskRatingRepo)
    {
        $ocena_zadania = $taskRatingRepo -> find($id);
        $this->validate($request, [
          'student_id' => 'required',
          'task_id' => 'required',
          'deadline' => 'required',
          'implementation_date' => 'date',
          'version' => 'required|integer|between:1,10',
          'importance' => 'required|numeric',
          'points' => 'nullable|numeric',
          'rating' => 'max:2',
          'comments' => 'max:50',
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

        return redirect($request->history_view);
    }

    public function destroy($id, TaskRatingRepository $taskRatingRepo)
    {
        $taskRatingRepo->delete($id);
        return redirect( $_SERVER['HTTP_REFERER'] );
    }
*/
}
