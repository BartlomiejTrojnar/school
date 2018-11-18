<?php
namespace App\Http\Controllers;
use App\Repositories\GroupRepository;
use App\Models\Group;

use App\Repositories\SubjectRepository;
use App\Models\LessonHour;
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
        return redirect( $_SERVER['HTTP_REFERER'] );
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

    public function show($id, $view='', GroupRepository $groupRepo, Group $group)
    {
        if(empty( session()->get('groupView') ))  session()->put('groupView', 'showInfo');
        if($view)  session()->put('groupView', $view);
        $group = $groupRepo -> find($id);
        $this->previous = $groupRepo -> PreviousRecordId($id);
        $this->next = $groupRepo -> NextRecordId($id);

        switch( session()->get('groupView') ) {
          case 'showInfo':
              return view('group.showInfo', ["group"=>$group, "previous"=>$this->previous, "next"=>$this->next]);
              exit;
          break;
          case 'showStudents':
              $groupStudents = $group -> students;
              return view('group.showStudents', ["group"=>$group, "groupStudents"=>$groupStudents, "previous"=>$this->previous, "next"=>$this->next]);
              exit;
          break;
          case 'showLessonPlan':
              $lessonHours = LessonHour::where('day', 'poniedziałek') -> get();
              return view('group.showLessonPlan', ["group"=>$group, "lessonHours"=>$lessonHours, "previous"=>$this->previous, "next"=>$this->next]);
              exit;
          break;
          default:
              echo 'Widok nieznany';
              exit;
          break;
        }
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

    public function hourSubtract($id, GroupRepository $groupRepo)
    {
        $group = $groupRepo -> find($id);
        $group->hours = $group->hours-1;
        $group->save();
        return $group->hours;
    }
    public function hourAdd($id, GroupRepository $groupRepo)
    {
        $group = $groupRepo -> find($id);
        $group->hours = $group->hours+1;
        $group->save();
        return $group->hours;
    }

    public function destroy(Group $grupa)
    {
        $grupa->delete();
        return redirect( $_SERVER['HTTP_REFERER'] );
    }
}
