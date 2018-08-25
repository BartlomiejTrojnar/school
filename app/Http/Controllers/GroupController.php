<?php
namespace App\Http\Controllers;
use App\Models\Group;
use App\Repositories\GroupRepository;
use App\Repositories\SubjectRepository;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function index(GroupRepository $groupRepo)
    {
        for($i=0; $i<6; $i++)
          $orderBy[$i] = session()->get("GroupOrderBy[$i]");

        $groups = $groupRepo->getAll($orderBy);
        return view('group.index', ["groups"=>$groups]);
    }

    public function orderBy($column)
    {
        if(session()->get('GroupOrderBy[0]') == $column)
          if(session()->get('GroupOrderBy[1]') == 'desc')
            session()->put('GroupOrderBy[1]', 'asc');
          else
            session()->put('GroupOrderBy[1]', 'desc');
        else
        {
          session()->put('GroupOrderBy[4]', session()->get('GroupOrderBy[2]'));
          session()->put('GroupOrderBy[2]', session()->get('GroupOrderBy[0]'));
          session()->put('GroupOrderBy[0]', $column);
          session()->put('GroupOrderBy[5]', session()->get('GroupOrderBy[3]'));
          session()->put('GroupOrderBy[3]', session()->get('GroupOrderBy[1]'));
          session()->put('GroupOrderBy[1]', 'asc');
        }
        return redirect( route('grupa.index') );
    }

    public function create(SubjectRepository $subjectRepo)
    {
        $subjects = $subjectRepo->getAll();
        $levels = array('podstawowy', 'rozszerzony', 'nieokreślony');
        $selectedLevel = 'podstawowy';
        return view('group.create')
             ->nest('subjectSelectField', 'subject.selectField', ["subjects"=>$subjects, "selectedSubject"=>0])
             ->nest('levelSelectField', 'layouts.levelSelectField', ["levels"=>$levels, "selectedLevel"=>$selectedLevel]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
          'subject_id' => 'required',
          'date_start' => 'required|date',
          'date_end' => 'required|date',
          'comments' => 'max:30',
          'hours' => 'integer|max:9',
        ]);

        $group = new Group;
        $group->subject_id = $request->subject_id;
        $group->date_start = $request->date_start;
        $group->date_end = $request->date_end;
        $group->comments = $request->comments;
        $group->level = $request->level;
        $group->hours = $request->hours;
        $group->save();

        return redirect($request->history_view);
    }

    public function show(Group $grupa, GroupRepository $groupRepo)
    {
        $previous = $groupRepo->previousRecordId($grupa->id);
        $next = $groupRepo->nextRecordId($grupa->id);
        return view('group.show', ["group"=>$grupa, "previous"=>$previous, "next"=>$next]);
    }

    public function edit(Group $grupa, SubjectRepository $subjectRepo)
    {
        $subjects = $subjectRepo->getAll();
        $levels = array('podstawowy', 'rozszerzony', 'nieokreślony');
        $selectedLevel = 'podstawowy';
        return view('group.edit', ["group"=>$grupa])
             ->nest('subjectSelectField', 'subject.selectField', ["subjects"=>$subjects, "selectedSubject"=>$grupa->subject_id])
             ->nest('levelSelectField', 'layouts.levelSelectField', ["levels"=>$levels, "selectedLevel"=>$grupa->level]);
    }

    public function update(Request $request, Group $grupa)
    {
        $this->validate($request, [
          'subject_id' => 'required',
          'date_start' => 'required|date',
          'date_end' => 'required|date',
          'comments' => 'max:30',
          'hours' => 'integer|max:9',
        ]);

        $grupa->subject_id = $request->subject_id;
        $grupa->date_start = $request->date_start;
        $grupa->date_end = $request->date_end;
        $grupa->comments = $request->comments;
        $grupa->level = $request->level;
        $grupa->hours = $request->hours;
        $grupa->save();

        return redirect($request->history_view);
    }

    public function destroy(Group $grupa)
    {
        $grupa->delete();
        return redirect( $_SERVER['HTTP_REFERER'] );
    }
}
