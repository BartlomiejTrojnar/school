<?php
namespace App\Http\Controllers;
//use App\Models\LessonHour;
//use App\Repositories\LessonHourRepository;
use Illuminate\Http\Request;

class LessonHourController extends Controller
{
    public function index(LessonHourRepository $lessonHourRepo)
    {
        $lessonHours = $lessonHourRepo->getAll();
        return view('lessonHour.index', ["lessonHours"=>$lessonHours]);
    }

    public function create()
    {
        return view('lessonHour.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
          'day' => 'required|max:12',
          'lesson_number' => 'required|integer|min:1|max:10',
          'start_time' => 'required',
          'end_time' => 'required',
        ]);

        $lessonHour = new LessonHour;
        $lessonHour->day = $request->day;
        $lessonHour->lesson_number = $request->lesson_number;
        $lessonHour->start_time = $request->start_time;
        $lessonHour->end_time = $request->end_time;
        $lessonHour->save();

        return redirect($request->history_view);
    }

    public function show(LessonHour $godzina, LessonHourRepository $lessonHourRepo)
    {
        $previous = $lessonHourRepo->previousRecordId($godzina->id);
        $next = $lessonHourRepo->nextRecordId($godzina->id);
        return view('lessonHour.show', ["lessonHour"=>$godzina, "previous"=>$previous, "next"=>$next]);
    }

    public function edit(LessonHour $godzina)
    {
        return view('lessonHour.edit', ["lessonHour"=>$godzina]);
    }

    public function update(Request $request, LessonHour $godzina)
    {
        $this->validate($request, [
          'day' => 'required|max:12',
          'lesson_number' => 'required|integer|min:1|max:10',
          'start_time' => 'required',
          'end_time' => 'required',
        ]);

        $godzina->day = $request->day;
        $godzina->lesson_number = $request->lesson_number;
        $godzina->start_time = $request->start_time;
        $godzina->end_time = $request->end_time;
        $godzina->save();

        return redirect($request->history_view);
    }

    public function destroy(LessonHour $godzina)
    {
        $godzina->delete();
        return redirect( $_SERVER['HTTP_REFERER'] );
    }
}
