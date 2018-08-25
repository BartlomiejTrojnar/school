<?php
namespace App\Http\Controllers;
use App\Models\GroupTeacher;
use App\Repositories\GroupTeacherRepository;
use App\Repositories\GroupRepository;
use App\Repositories\TeacherRepository;
use Illuminate\Http\Request;

class GroupTeacherController extends Controller
{
    public function index(GroupTeacherRepository $groupTeacherRepo)
    {
        for($i=0; $i<6; $i++)
          $orderBy[$i] = session()->get("GroupTeacherOrderBy[$i]");

        $groupTeachers = $groupTeacherRepo->getAll($orderBy);
        return view('groupTeacher.index', ["groupTeachers"=>$groupTeachers]);
    }

    public function orderBy($column)
    {
        if(session()->get('GroupTeacherOrderBy[0]') == $column)
          if(session()->get('GroupTeacherOrderBy[1]') == 'desc')
            session()->put('GroupTeacherOrderBy[1]', 'asc');
          else
            session()->put('GroupTeacherOrderBy[1]', 'desc');
        else
        {
          session()->put('GroupTeacherOrderBy[4]', session()->get('GroupTeacherOrderBy[2]'));
          session()->put('GroupTeacherOrderBy[2]', session()->get('GroupTeacherOrderBy[0]'));
          session()->put('GroupTeacherOrderBy[0]', $column);
          session()->put('GroupTeacherOrderBy[5]', session()->get('GroupTeacherOrderBy[3]'));
          session()->put('GroupTeacherOrderBy[3]', session()->get('GroupTeacherOrderBy[1]'));
          session()->put('GroupTeacherOrderBy[1]', 'asc');
        }
        return redirect( route('grupa_nauczyciele.index') );
    }

    public function create(GroupRepository $groupRepo, TeacherRepository $teacherRepo)
    {
        $groups = $groupRepo->getAll();
        $teachers = $teacherRepo->getAll();
        return view('groupTeacher.create')
             ->nest('groupSelectField', 'group.selectField', ["groups"=>$groups, "selectedGroup"=>0])
             ->nest('teacherSelectField', 'teacher.selectField', ["teachers"=>$teachers, "selectedTeacher"=>0]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
          'group_id' => 'required',
          'teacher_id' => 'required',
          'date_start' => 'required|date',
          'date_end' => 'required|date',
        ]);

        $groupTeacher = new GroupTeacher;
        $groupTeacher->group_id = $request->group_id;
        $groupTeacher->teacher_id = $request->teacher_id;
        $groupTeacher->date_start = $request->date_start;
        $groupTeacher->date_end = $request->date_end;
        $groupTeacher->save();

        return redirect($request->history_view);
    }

    public function show(GroupTeacher $grupa_nauczyciele, GroupTeacherRepository $groupTeacherRepo)
    {
        $previous = $groupTeacherRepo->previousRecordId($grupa_nauczyciele->id);
        $next = $groupTeacherRepo->nextRecordId($grupa_nauczyciele->id);
        return view('groupTeacher.show', ["groupTeacher"=>$grupa_nauczyciele, "previous"=>$previous, "next"=>$next]);
    }

    public function edit(GroupTeacher $grupa_nauczyciele, GroupRepository $groupRepo, TeacherRepository $teacherRepo)
    {
        $groups = $groupRepo->getAll();
        $teachers = $teacherRepo->getAll();
        return view('groupTeacher.edit', ["groupTeacher"=>$grupa_nauczyciele])
             ->nest('groupSelectField', 'group.selectField', ["groups"=>$groups, "selectedGroup"=>$grupa_nauczyciele->group_id])
             ->nest('teacherSelectField', 'teacher.selectField', ["teachers"=>$teachers, "selectedTeacher"=>$grupa_nauczyciele->teacher_id]);
    }

    public function update(Request $request, GroupTeacher $grupa_nauczyciele)
    {
        $this->validate($request, [
          'group_id' => 'required',
          'teacher_id' => 'required',
          'date_start' => 'required|date',
          'date_end' => 'required|date',
        ]);

        $grupa_nauczyciele->group_id = $request->group_id;
        $grupa_nauczyciele->teacher_id = $request->teacher_id;
        $grupa_nauczyciele->date_start = $request->date_start;
        $grupa_nauczyciele->date_end = $request->date_end;
        $grupa_nauczyciele->save();

        return redirect($request->history_view);
    }

    public function destroy(GroupTeacher $grupa_nauczyciele)
    {
        $grupa_nauczyciele->delete();
        return redirect( $_SERVER['HTTP_REFERER'] );
    }
}
