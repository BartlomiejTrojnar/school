<?php
namespace App\Http\Controllers;
use App\Models\Lesson;

use App\Repositories\GroupRepository;
use App\Repositories\TeacherRepository;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    public function orderBy($column) {
        if(session()->get('LessonOrderBy[0]') == $column)
            if(session()->get('LessonOrderBy[1]') == 'desc')  session()->put('LessonOrderBy[1]', 'asc');
            else session()->put('LessonOrderBy[1]', 'desc');
        else {
            session()->put('LessonOrderBy[4]', session()->get('LessonOrderBy[2]'));
            session()->put('LessonOrderBy[2]', session()->get('LessonOrderBy[0]'));
            session()->put('LessonOrderBy[0]', $column);
            session()->put('LessonOrderBy[5]', session()->get('LessonOrderBy[3]'));
            session()->put('LessonOrderBy[3]', session()->get('LessonOrderBy[1]'));
            session()->put('LessonOrderBy[1]', 'asc');
        }
        return redirect( $_SERVER['HTTP_REFERER'] );
    }

    public function create(GroupRepository $groupRepo, TeacherRepository $teacherRepo) {
        $groups = $groupRepo -> getGroups('2020-02-05');
        $teachers = $teacherRepo -> getTeachers();
        $lessonTypes = array('normalna', 'zastępstwo', 'nieodbyta');

        $groupSelected = session()->get('groupSelected');
        $group = $groupRepo -> find($groupSelected);
        $teacherSelected = $group->teachers[0]->teacher->id;
        $lessonTypeSelected = 'normalna';

        return view('lesson.create')
            -> nest('groupSelectField', 'group.selectField', ["groups"=>$groups, "groupSelected"=>$groupSelected])
            -> nest('teacherSelectField', 'teacher.selectField', ["teachers"=>$teachers, "teacherSelected"=>$teacherSelected])
            -> nest('lessonTypeSelectField', 'lesson.lessonTypeSelectField', ["lessonTypes"=>$lessonTypes, "lessonTypeSelected"=>$lessonTypeSelected]);
    }

    public function store(Request $request) {
        $this->validate($request, [
          'group_id' => 'required',
          'teacher_id' => 'required',
          'date' => 'required|date',
          'length' => 'required|integer|min:1|max:45',
          'lesson_type' => 'required',
          'number' => 'integer|min:0|max:250',
          'topic_entered' => 'max:50',
          'topic_completed' => 'max:50',
          'comments' => 'max:20',
        ]);

        $lesson = new Lesson;
        $lesson->group_id = $request->group_id;
        $lesson->teacher_id = $request->teacher_id;
        $lesson->date = $request->date;
        $lesson->length = $request->length;
        $lesson->type = $request->lesson_type;
        $lesson->number = $request->number;
        $lesson->topic_entered = $request->topic_entered;
        $lesson->topic_completed = $request->topic_completed;
        $lesson->comments = $request->comments;
        $lesson->save();

        return redirect($request->history_view);
    }

    public function edit($id, Lesson $lesson, GroupRepository $groupRepo, TeacherRepository $teacherRepo) {
        $lesson = $lesson -> find($id);
        $groups = $groupRepo -> getGroups('2020-02-05');
        $teachers = $teacherRepo -> getTeachers();
        $lessonTypes = array('normalna', 'zastępstwo', 'nieodbyta');

        return view('lesson.edit', ["lesson"=>$lesson])
            -> nest('groupSelectField', 'group.selectField', ["groups"=>$groups, "groupSelected"=>$lesson->group_id])
            -> nest('teacherSelectField', 'teacher.selectField', ["teachers"=>$teachers, "teacherSelected"=>$lesson->teacher_id])
            -> nest('lessonTypeSelectField', 'lesson.lessonTypeSelectField', ["lessonTypes"=>$lessonTypes, "lessonTypeSelected"=>$lesson->type]);
    }

    public function update($id, Request $request, Lesson $lesson) {
        $this->validate($request, [
            'group_id' => 'required',
            'teacher_id' => 'required',
            'date' => 'required|date',
            'length' => 'required|integer|min:1|max:45',
            'lesson_type' => 'required',
            'number' => 'integer|min:0|max:250',
            'topic_entered' => 'max:50',
            'topic_completed' => 'max:50',
            'comments' => 'max:20',
        ]);

        $lesson = $lesson -> find($id);
        $lesson->group_id = $request->group_id;
        $lesson->teacher_id = $request->teacher_id;
        $lesson->date = $request->date;
        $lesson->length = $request->length;
        $lesson->type = $request->lesson_type;
        $lesson->number = $request->number;
        $lesson->topic_entered = $request->topic_entered;
        $lesson->topic_completed = $request->topic_completed;
        $lesson->comments = $request->comments;
        $lesson->save();

        return redirect($request->history_view);
    }

    public function destroy($id, Lesson $lesson)  {
        $lesson = $lesson -> find($id);
        $lesson -> delete();
        return redirect( $_SERVER['HTTP_REFERER'] );
    }
}
