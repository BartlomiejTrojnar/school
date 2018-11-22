<?php
namespace App\Http\Controllers;
//use App\Models\Lesson;
//use App\Repositories\LessonRepository;
//use App\Repositories\GroupRepository;
//use App\Repositories\TeacherRepository;
use Illuminate\Http\Request;

class LessonController extends Controller
{
/*
    public function index(LessonRepository $lessonRepo)
    {
        for($i=0; $i<6; $i++)
          $orderBy[$i] = session()->get("LessonOrderBy[$i]");

        $lessons = $lessonRepo->getAll($orderBy);
        return view('lesson.index', ["lessons"=>$lessons]);
    }

    public function orderBy($column)
    {
        if(session()->get('LessonOrderBy[0]') == $column)
          if(session()->get('LessonOrderBy[1]') == 'desc')
            session()->put('LessonOrderBy[1]', 'asc');
          else
            session()->put('LessonOrderBy[1]', 'desc');
        else
        {
          session()->put('LessonOrderBy[4]', session()->get('LessonOrderBy[2]'));
          session()->put('LessonOrderBy[2]', session()->get('LessonOrderBy[0]'));
          session()->put('LessonOrderBy[0]', $column);
          session()->put('LessonOrderBy[5]', session()->get('LessonOrderBy[3]'));
          session()->put('LessonOrderBy[3]', session()->get('LessonOrderBy[1]'));
          session()->put('LessonOrderBy[1]', 'asc');
        }
        return redirect( route('lekcja.index') );
    }

    public function create(GroupRepository $groupRepo, TeacherRepository $teacherRepo)
    {
        $groups = $groupRepo->getAll();
        $teachers = $teacherRepo->getAll();
        return view('lesson.create')
             ->nest('groupSelectField', 'group.selectField', ["groups"=>$groups, "selectedGroup"=>0])
             ->nest('teacherSelectField', 'teacher.selectField', ["teachers"=>$teachers, "selectedTeacher"=>0]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
          'group_id' => 'required',
          'teacher_id' => 'required',
          'lesson_date' => 'required|date',
          'lesson_length' => 'required|integer|min:1|max:45',
          'number' => 'integer|min:0|max:250',
          'topic_entered' => 'max:45',
          'topic_realized' => 'max:45',
          'comments' => 'max:45',
        ]);

        $lesson = new Lesson;
        $lesson->group_id = $request->group_id;
        $lesson->teacher_id = $request->teacher_id;
        $lesson->lesson_date = $request->lesson_date;
        $lesson->lesson_length = $request->lesson_length;
        $lesson->number = $request->number;
        $lesson->topic_entered = $request->topic_entered;
        $lesson->topic_realized = $request->topic_realized;
        $lesson->comments = $request->comments;
        $lesson->save();

        return redirect($request->history_view);
    }

    public function show(Lesson $lekcja, LessonRepository $lessonRepo)
    {
        $previous = $lessonRepo->previousRecordId($lekcja->id);
        $next = $lessonRepo->nextRecordId($lekcja->id);
        return view('lesson.show', ["lesson"=>$lekcja, "previous"=>$previous, "next"=>$next]);
    }

    public function edit(Lesson $lekcja, GroupRepository $groupRepo, TeacherRepository $teacherRepo)
    {
        $groups = $groupRepo->getAll();
        $teachers = $teacherRepo->getAll();
        return view('lesson.edit', ["lesson"=>$lekcja])
             ->nest('groupSelectField', 'group.selectField', ["groups"=>$groups, "selectedGroup"=>$lekcja->group_id])
             ->nest('teacherSelectField', 'teacher.selectField', ["teachers"=>$teachers, "selectedTeacher"=>$lekcja->teacher_id]);
    }

    public function update(Request $request, Lesson $lekcja)
    {
        $this->validate($request, [
          'group_id' => 'required',
          'teacher_id' => 'required',
          'lesson_date' => 'required|date',
          'lesson_length' => 'required|integer|min:1|max:45',
          'number' => 'integer|min:0|max:250',
          'topic_entered' => 'max:45',
          'topic_realized' => 'max:45',
          'comments' => 'max:45',
        ]);

        $lekcja->group_id = $request->group_id;
        $lekcja->teacher_id = $request->teacher_id;
        $lekcja->lesson_date = $request->lesson_date;
        $lekcja->lesson_length = $request->lesson_length;
        $lekcja->number = $request->number;
        $lekcja->topic_entered = $request->topic_entered;
        $lekcja->topic_realized = $request->topic_realized;
        $lekcja->comments = $request->comments;
        $lekcja->save();

        return redirect($request->history_view);
    }

    public function destroy(Lesson $lekcja)
    {
        $lekcja->delete();
        return redirect( $_SERVER['HTTP_REFERER'] );
    }
*/
}
