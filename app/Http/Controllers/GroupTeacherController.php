<?php
// ------------------------ (C) mgr inż. Bartłomiej Trojnar; 07.01.2022 ------------------------ //
namespace App\Http\Controllers;
use App\Models\GroupTeacher;
use App\Repositories\GroupTeacherRepository;

use App\Repositories\GroupRepository;
use App\Repositories\SchoolYearRepository;
use App\Repositories\TeacherRepository;
use Illuminate\Http\Request;

class GroupTeacherController extends Controller
{
    public function addTeacher($group_id, TeacherRepository $teacherRepo, GroupRepository $groupRepo, SchoolYearRepository $schoolYearRepo) {
        $group = $groupRepo -> find($group_id);
        $schoolYear_id = $schoolYearRepo -> getSchoolYearIdForDate( $group->start );
        $teachersForGroup = $teacherRepo -> getTeachersForGroup( $group, $schoolYear_id );

        $start[0] = $group->start;
        $start[1] = $group->start;
        $start[2] = session() -> get('dateView');
        $end[0] = $group->end;
        $end[1] = $group->end;
        $teacherSelected = session() -> get('teacherSelected');
        $teacherSelectField = view('teacher.selectField', ["teachers"=>$teachersForGroup, "teacherSelected"=>$teacherSelected]);
        return view('groupTeacher.addTeacher', ["group_id"=>$group_id, "start"=>$start, "end"=>$end, "history_view"=>'', "teacherSelectField"=>$teacherSelectField]);
    }

    // zamiana nauczyciela uczącego w grupie
    public function changeTeacher(Request $request, GroupTeacher $groupTeacher, GroupTeacherRepository $groupTeacherRepo, GroupRepository $groupRepo, TeacherRepository $teacherRepo) {
        $group = $groupRepo -> find($request->group_id);
        //sprawdzenie czy wpisane są poprawne daty
        if( $request->date_start > $request->date_end ||
            $request->date_start == '' ||
            $request->date_end   == '' ||
            $request->date_start < $group->date_start ||
            $request->date_end > $group->date_end )
        {
            //$groups = $groupRepo -> getAll();
            $teachers = $teacherRepo -> getAll();
            $date_start[0] = $request->date_start;
            $date_start[1] = $group->date_start;
            $date_start[2] = date('Y-m-d');
            $date_end[0] = $request->date_end;
            $date_end[1] = $group->date_end;
            return view('groupTeacher.addTeacher', ["groupTeacher"=>$groupTeacher, "group_id"=>$request->group_id, "date_start"=>$date_start, "date_end"=>$date_end, "history_view"=>$request->history_view])
                -> nest('teacherSelectField', 'teacher.selectField', ["teachers"=>$teachers, "teacherSelected"=>$request->teacher_id ]);
        }

        // wyszukanie i zmiana daty zakończenia nauki grupy dla nauczycieli grupy
        $groupTeachers = $groupTeacherRepo -> getGroupTeacherForGroup($request->group_id);
        foreach($groupTeachers as $gt) {
            // zignorowanie nauczycieli, którzy rozpoczęli naukę grupy później niż podana data początkowa
            if($gt->date_start > $request->date_start) continue;
            // zignorowanie nauczycieli, którzy zakończyli naukę grupy wcześniej niż podana data początkowa
            $dateEnd =  date('Y-m-d', strtotime('-1 day', strtotime($request->date_start)));
            if($gt->date_end <= $dateEnd) continue;

            // zmiana daty zakończenia nauki dla pozostałych nauczycieli
            $groupTeacher = $groupTeacher -> find($gt->id);
            $groupTeacher->date_end = $dateEnd;
            $groupTeacher -> save();
        }

        // dodanie nowego nauczyciela do grupy
        $groupTeacher = new GroupTeacher;
        $groupTeacher->group_id = $request->group_id;
        $groupTeacher->teacher_id = $request->teacher_id;
        $groupTeacher->date_start = $request->date_start;
        $groupTeacher->date_end = $request->date_end;
        $groupTeacher -> save();

        echo $request->history_view;

        return redirect($request->history_view);
    }

    public function store(Request $request, TeacherRepository $teacherRepo, GroupRepository $groupRepo) {
        $this -> validate($request, [
          'group_id' => 'required',
          'teacher_id' => 'required',
        ]);
        $group = $groupRepo -> find($request->group_id);
        if( $request->start > $request->end ||
            $request->start == '' ||
            $request->end   == '' ||
            $request->start < $group->start ||
            $request->end > $group->end )
        {
            $teachers = $teacherRepo -> getAll();
            $start[0] = $request->start;
            $start[1] = $group->start;
            $start[2] = date('Y-m-d');
            $end[0] = $request->end;
            $end[1] = $group->end;
            return view('groupTeacher.addTeacher', ["group_id"=>$request->group_id, "start"=>$start, "end"=>$end, "history_view"=>$request->history_view])
                -> nest('teacherSelectField', 'teacher.selectField', ["teachers"=>$teachers, "selectedTeacher"=>$request->teacher_id ]);
        }

        $groupTeacher = new GroupTeacher;
        $groupTeacher->group_id = $request->group_id;
        $groupTeacher->teacher_id = $request->teacher_id;
        $groupTeacher->start = $request->start;
        $groupTeacher->end = $request->end;
        $groupTeacher -> save();
        if( strpos($request->history_view, 'grupa/create') )  return redirect( route('grupa_klasy.gradesList', $groupTeacher->group_id.'/forIndex') );
        else  return redirect( route('grupa.show', $groupTeacher->group_id) );
    }

    public function automaticallyAddTeacher(Request $request) {
        $groupTeacher = new GroupTeacher;
        $groupTeacher->group_id = $request->group_id;
        $groupTeacher->teacher_id = $request->teacher_id;
        $groupTeacher->date_start = $request->date_start;
        $groupTeacher->date_end = $request->date_end;
        $groupTeacher -> save();
        $url = 'grupa_klasy/gradesList/'.$groupTeacher->group_id. '/forTeacher';
        return redirect($url);
    }

    public function edit($id, GroupTeacher $groupTeacher, GroupRepository $groupRepo, TeacherRepository $teacherRepo, SchoolYearRepository $schoolYearRepo) {
        $groupTeacher = $groupTeacher -> find($id);
        $group = $groupRepo -> find($groupTeacher->group_id);
        $schoolYear_id = $schoolYearRepo -> getSchoolYearIdForDate( $group->date_start );
        $teachers = $teacherRepo -> getTeachersForGroup( $group, $schoolYear_id );

        $date_start[0] = $groupTeacher->date_start;
        $date_start[1] = $group->date_start;
        $date_start[2] = date('Y-m-d');
        $date_end[0] = $groupTeacher->date_end;
        $date_end[1] = $group->date_end;

        return view('groupTeacher.edit', ["groupTeacher"=>$groupTeacher, "group_id"=>$groupTeacher->group_id, "date_start"=>$date_start, "date_end"=>$date_end, "history_view"=>''])
            -> nest('teacherSelectField', 'teacher.selectField', ["teachers"=>$teachers, "teacherSelected"=>$groupTeacher->teacher_id]);
    }

    public function update($id, Request $request, GroupTeacher $groupTeacher, GroupRepository $groupRepo, TeacherRepository $teacherRepo) {
        $groupTeacher = $groupTeacher -> find($id);
        $this -> validate($request, [
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
            //$groups = $groupRepo -> getAll();
            $teachers = $teacherRepo -> getAll();
            $date_start[0] = $request->date_start;
            $date_start[1] = $group->date_start;
            $date_start[2] = date('Y-m-d');
            $date_end[0] = $request->date_end;
            $date_end[1] = $group->date_end;
            return view('groupTeacher.edit', ["groupTeacher"=>$groupTeacher, "group_id"=>$request->group_id, "date_start"=>$date_start, "date_end"=>$date_end, "history_view"=>$request->history_view])
                -> nest('teacherSelectField', 'teacher.selectField', ["teachers"=>$teachers, "teacherSelected"=>$request->teacher_id ]);
        }

        $groupTeacher->group_id = $request->group_id;
        $groupTeacher->teacher_id = $request->teacher_id;
        $groupTeacher->date_start = $request->date_start;
        $groupTeacher->date_end = $request->date_end;
        $groupTeacher -> save();

        return redirect( route('grupa.show', $request->group_id) );
    }

    public function destroy($id, GroupTeacher $groupTeacher) {
        $groupTeacher = $groupTeacher -> find($id);
        $groupTeacher -> delete();
        return;
    }
}