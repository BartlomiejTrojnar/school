<?php
namespace App\Http\Controllers;
use App\Models\GroupTeacher;
use App\Repositories\GroupRepository;
use App\Repositories\TeacherRepository;
use Illuminate\Http\Request;

class GroupTeacherController extends Controller
{
    public function addTeacher($group_id, TeacherRepository $teacherRepo, GroupRepository $groupRepo)
    {
        $teachers = $teacherRepo->getAll();
        $group = $groupRepo -> find($group_id);
        $date_start[0] = $group->date_start;
        $date_start[1] = $group->date_start;
        $date_start[2] = date('Y-m-d');
        $date_end[0] = $group->date_end;
        $date_end[1] = $group->date_end;
        return view('groupTeacher.addTeacher', ["group_id"=>$group_id, "date_start"=>$date_start, "date_end"=>$date_end, "history_view"=>''])
             ->nest('teacherSelectField', 'teacher.selectField', ["teachers"=>$teachers, "selectedTeacher"=>0]);
    }

    public function store(Request $request, TeacherRepository $teacherRepo, GroupRepository $groupRepo)
    {
        $this->validate($request, [
          'group_id' => 'required',
          'teacher_id' => 'required',
        ]);
        $group = $groupRepo -> find($request->group_id);
        if( $request->date_start > $request->date_end ||
            $request->date_start == '' ||
            $request->date_end   == '' ||
            $request->date_start < $group->date_start ||
            $request->date_end > $group->date_end )
        {
          $teachers = $teacherRepo->getAll();
          $date_start[0] = $request->date_start;
          $date_start[1] = $group->date_start;
          $date_start[2] = date('Y-m-d');
          $date_end[0] = $request->date_end;
          $date_end[1] = $group->date_end;
          return view('groupTeacher.addTeacher', ["group_id"=>$request->group_id, "date_start"=>$date_start, "date_end"=>$date_end, "history_view"=>$request->history_view])
             ->nest('teacherSelectField', 'teacher.selectField', ["teachers"=>$teachers, "selectedTeacher"=>$request->teacher_id ]);
        }

        $groupTeacher = new GroupTeacher;
        $groupTeacher->group_id = $request->group_id;
        $groupTeacher->teacher_id = $request->teacher_id;
        $groupTeacher->date_start = $request->date_start;
        $groupTeacher->date_end = $request->date_end;
        $groupTeacher->save();

        return redirect($request->history_view);
    }

    public function edit(GroupTeacher $grupa_nauczyciele, GroupRepository $groupRepo, TeacherRepository $teacherRepo)
    {
        $teachers = $teacherRepo->getAll();
        $group = $groupRepo -> find($grupa_nauczyciele->group_id);
        $date_start[0] = $grupa_nauczyciele->date_start;
        $date_start[1] = $group->date_start;
        $date_start[2] = date('Y-m-d');
        $date_end[0] = $grupa_nauczyciele->date_end;
        $date_end[1] = $group->date_end;

        return view('groupTeacher.edit', ["groupTeacher"=>$grupa_nauczyciele, "group_id"=>$grupa_nauczyciele->group_id, "date_start"=>$date_start, "date_end"=>$date_end, "history_view"=>''])
             ->nest('teacherSelectField', 'teacher.selectField', ["teachers"=>$teachers, "selectedTeacher"=>$grupa_nauczyciele->teacher_id]);
    }

    public function update(Request $request, GroupTeacher $grupa_nauczyciele, GroupRepository $groupRepo, TeacherRepository $teacherRepo)
    {
        $this->validate($request, [
          'group_id' => 'required',
          'teacher_id' => 'required',
        ]);
        $group = $groupRepo -> find($request->group_id);

        if( $request->date_start > $request->date_end ||
            $request->date_start == '' ||
            $request->date_end   == '' ||
            $request->date_start < $group->date_start ||
            $request->date_end > $group->date_end )
        {
          $groups = $groupRepo->getAll();
          $teachers = $teacherRepo->getAll();
          $date_start[0] = $request->date_start;
          $date_start[1] = $group->date_start;
          $date_start[2] = date('Y-m-d');
          $date_end[0] = $request->date_end;
          $date_end[1] = $group->date_end;
          return view('groupTeacher.edit', ["groupTeacher"=>$grupa_nauczyciele, "group_id"=>$request->group_id, "date_start"=>$date_start, "date_end"=>$date_end, "history_view"=>$request->history_view])
             ->nest('teacherSelectField', 'teacher.selectField', ["teachers"=>$teachers, "selectedTeacher"=>$request->teacher_id ]);
        }

        $grupa_nauczyciele->group_id = $request->group_id;
        $grupa_nauczyciele->teacher_id = $request->teacher_id;
        $grupa_nauczyciele->date_start = $request->date_start;
        $grupa_nauczyciele->date_end = $request->date_end;
        $grupa_nauczyciele->save();

        return redirect($request->history_view);
    }

    public function destroy(GroupTeacher $grupa_nauczyciele)
    {
        print_r( $grupa_nauczyciele->delete() );
        return;
    }

}
