<?php
namespace App\Http\Controllers;
//use App\Models\CommandRating;
//use App\Repositories\CommandRatingRepository;
//use App\Repositories\CommandRepository;
//use App\Repositories\TaskRatingRepository;
use Illuminate\Http\Request;

class CommandRatingController extends Controller
{
/*
    public function index(CommandRatingRepository $commandRatingRepo)
    {
        $commandRatings = $commandRatingRepo->getAll($orderBy);
        return view('command.index')
            -> nest('commandTable', 'command.table', ["commands"=>$commands, "subTitle"=>""]);
    }

    public function orderBy($column)
    {
        if(session()->get('CommandRatingOrderBy[0]') == $column)
          if(session()->get('CommandRatingOrderBy[1]') == 'desc')
            session()->put('CommandRatingOrderBy[1]', 'asc');
          else
            session()->put('CommandRatingOrderBy[1]', 'desc');
        else
        {
          session()->put('CommandRatingOrderBy[4]', session()->get('CommandRatingOrderBy[2]'));
          session()->put('CommandRatingOrderBy[2]', session()->get('CommandRatingOrderBy[0]'));
          session()->put('CommandRatingOrderBy[0]', $column);
          session()->put('CommandRatingOrderBy[5]', session()->get('CommandRatingOrderBy[3]'));
          session()->put('CommandRatingOrderBy[3]', session()->get('CommandRatingOrderBy[1]'));
          session()->put('CommandRatingOrderBy[1]', 'asc');
        }
        return redirect( route('ocena_polecenia.index') );
    }

    public function create(CommandRepository $commandRepo, TaskRatingRepository $taskRatingRepo)
    {
        $commands = $commandRepo->getAll();
        $taskRatings = $taskRatingRepo->getAll();
        return view('commandRating.create')
             ->nest('commandSelectField', 'command.selectField', ["commands"=>$commands, "selectedCommand"=>0])
             ->nest('taskRatingSelectField', 'taskRating.selectField', ["taskRatings"=>$taskRatings, "selectedTaskRating"=>0]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
          'command_id' => 'required',
          'task_rating_id' => 'required',
          'points' => 'numeric',
        ]);

        $commandRating = new CommandRating;
        $commandRating->command_id = $request->command_id;
        $commandRating->task_rating_id = $request->task_rating_id;
        $commandRating->points = $request->points;
        $commandRating->save();

        return redirect($request->history_view);
    }

    public function show(CommandRating $ocena_polecenia, CommandRatingRepository $commandRatingRepo)
    {
        $previous = $commandRatingRepo->previousRecordId($ocena_polecenia->id);
        $next = $commandRatingRepo->nextRecordId($ocena_polecenia->id);
        return view('commandRating.show', ["commandRating"=>$ocena_polecenia, "previous"=>$previous, "next"=>$next]);
    }

    public function edit($id, CommandRatingRepository $commandRatingRepo, CommandRepository $commandRepo, TaskRatingRepository $taskRatingRepo)
    {
        $ocena_polecenia = $commandRatingRepo->find($id);
        $commands = $commandRepo->getAll();
        $taskRatings = $taskRatingRepo->getAll();
        return view('commandRating.edit', ["commandRating"=>$ocena_polecenia])
             ->nest('commandSelectField', 'command.selectField', ["commands"=>$commands, "selectedCommand"=>$ocena_polecenia->command_id])
             ->nest('taskRatingSelectField', 'taskRating.selectField', ["taskRatings"=>$taskRatings, "selectedTaskRating"=>$ocena_polecenia->task_rating_id]);
    }

    public function update(Request $request, $id, CommandRatingRepository $commandRatingRepo)
    {
        $ocena_polecenia = $commandRatingRepo->find($id);
        $this->validate($request, [
          'command_id' => 'required',
          'task_rating_id' => 'required',
          'points' => 'numeric',
        ]);

        $ocena_polecenia->command_id = $request->command_id;
        $ocena_polecenia->task_rating_id = $request->task_rating_id;
        $ocena_polecenia->points = $request->points;
        $ocena_polecenia->save();

        return redirect($request->history_view);
    }

    public function destroy($id, CommandRatingRepository $commandRatingRepo)
    {
        $commandRatingRepo->delete($id);
        return redirect( $_SERVER['HTTP_REFERER'] );
    }
*/
}
