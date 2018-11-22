<?php
namespace App\Http\Controllers;
use App\Models\LessonPlan;
use App\Repositories\LessonPlanRepository;
//use App\Repositories\GroupRepository;
//use App\Repositories\LessonHourRepository;
//use App\Repositories\ClassroomRepository;
use Illuminate\Http\Request;

class LessonPlanController extends Controller
{
    public function index(LessonPlanRepository $lessonPlanRepo)
    {
        for($i=0; $i<6; $i++)
          $orderBy[$i] = session()->get("LessonPlanOrderBy[$i]");

        $lessonPlans = $lessonPlanRepo->getAll($orderBy);
        return view('lessonPlan.index', ["lessonPlans"=>$lessonPlans]);
    }

    public function findLesson($group_id, $hour_id)
    {
        $lessons = LessonPlan::where('group_id', $group_id) -> where('lesson_hour_id', $hour_id) -> get();
        return view('lessonPlan.groupLesson', ["lessons"=>$lessons]);
    }

    public function orderBy($column)
    {
        if(session()->get('LessonPlanOrderBy[0]') == $column)
          if(session()->get('LessonPlanOrderBy[1]') == 'desc')
            session()->put('LessonPlanOrderBy[1]', 'asc');
          else
            session()->put('LessonPlanOrderBy[1]', 'desc');
        else
        {
          session()->put('LessonPlanOrderBy[4]', session()->get('LessonPlanOrderBy[2]'));
          session()->put('LessonPlanOrderBy[2]', session()->get('LessonPlanOrderBy[0]'));
          session()->put('LessonPlanOrderBy[0]', $column);
          session()->put('LessonPlanOrderBy[5]', session()->get('LessonPlanOrderBy[3]'));
          session()->put('LessonPlanOrderBy[3]', session()->get('LessonPlanOrderBy[1]'));
          session()->put('LessonPlanOrderBy[1]', 'asc');
        }
        return redirect( route('plan_lekcji.index') );
    }

    public function create(GroupRepository $groupRepo, LessonHourRepository $lessonHourRepo, ClassroomRepository $classroomRepo)
    {
        $groups = $groupRepo->getAll();
        $lessonHours = $lessonHourRepo->getAll();
        $classrooms = $classroomRepo->getAll();
        return view('lessonPlan.create')
             ->nest('groupSelectField', 'group.selectField', ["groups"=>$groups, "selectedGroup"=>0])
             ->nest('lessonHourSelectField', 'lessonHour.selectField', ["lessonHours"=>$lessonHours, "selectedLessonHour"=>0])
             ->nest('classroomSelectField', 'classroom.selectField', ["classrooms"=>$classrooms, "selectedClassroom"=>0]);
    }

    public function addLesson($group_id, $hour_id)
    {
        $lessonPlan = new LessonPlan;
        $lessonPlan->group_id = $group_id;
        $lessonPlan->lesson_hour_id = $hour_id;
        $lessonPlan->date_start = session()->get('dateSession');
        $lessonPlan->date_end = '2019-06-30';
        $lessonPlan->save();

        return;
    }

    public function store(Request $request)
    {
        $this->validate($request, [
          'group_id' => 'required',
          'lesson_hour_id' => 'required',
          'date_start' => 'required|date',
          'date_end' => 'required|date',
        ]);

        $lessonPlan = new LessonPlan;
        $lessonPlan->group_id = $request->group_id;
        $lessonPlan->lesson_hour_id = $request->lesson_hour_id;
        $lessonPlan->classroom_id = $request->classroom_id;
        $lessonPlan->date_start = $request->date_start;
        $lessonPlan->date_end = $request->date_end;
        $lessonPlan->save();

        return redirect($request->history_view);
    }

    public function show(LessonPlan $plan_lekcji, LessonPlanRepository $lessonPlanRepo)
    {
        $previous = $lessonPlanRepo->previousRecordId($plan_lekcji->id);
        $next = $lessonPlanRepo->nextRecordId($plan_lekcji->id);
        return view('lessonPlan.show', ["lessonPlan"=>$plan_lekcji, "previous"=>$previous, "next"=>$next]);
    }

    public function edit(LessonPlan $plan_lekcji, GroupRepository $groupRepo, LessonHourRepository $lessonHourRepo, ClassroomRepository $classroomRepo)
    {
        $groups = $groupRepo->getAll();
        $lessonHours = $lessonHourRepo->getAll();
        $classrooms = $classroomRepo->getAll();
        return view('lessonPlan.edit', ["lessonPlan"=>$plan_lekcji])
             ->nest('groupSelectField', 'group.selectField', ["groups"=>$groups, "selectedGroup"=>$plan_lekcji->group_id])
             ->nest('lessonHourSelectField', 'lessonHour.selectField', ["lessonHours"=>$lessonHours, "selectedLessonHour"=>$plan_lekcji->lesson_hour_id])
             ->nest('classroomSelectField', 'classroom.selectField', ["classrooms"=>$classrooms, "selectedClassroom"=>$plan_lekcji->classroom_id]);
    }

    public function update(Request $request, LessonPlan $plan_lekcji)
    {
        $this->validate($request, [
          'group_id' => 'required',
          'lesson_hour_id' => 'required',
          'date_start' => 'required|date',
          'date_end' => 'required|date',
        ]);

        $plan_lekcji->group_id = $request->group_id;
        $plan_lekcji->lesson_hour_id = $request->lesson_hour_id;
        $plan_lekcji->classroom_id = $request->classroom_id;
        $plan_lekcji->date_start = $request->date_start;
        $plan_lekcji->date_end = $request->date_end;
        $plan_lekcji->save();

        return redirect($request->history_view);
    }

    public function destroy(LessonPlan $plan_lekcji)
    {
        $plan_lekcji->delete();
        return redirect( $_SERVER['HTTP_REFERER'] );
    }
}
