<?php
namespace App\Http\Controllers;
//use App\Models\Term;
//use App\Repositories\TermRepository;
//use App\Repositories\ExamDescriptionRepository;
//use App\Repositories\ClassroomRepository;
use Illuminate\Http\Request;

class TermController extends Controller
{
/*
    public function index(TermRepository $termRepo, ExamDescriptionRepository $examDescriptionRepo, ClassroomRepository $classroomRepo)
    {
        for($i=0; $i<6; $i++)
          $orderBy[$i] = session()->get("TermOrderBy[$i]");

        $terms = $termRepo->getAll($orderBy);
        $examDescriptions = $examDescriptionRepo->getAll();
        $classrooms = $classroomRepo->getAll();
        return view('term.index', ["terms"=>$terms])
             ->nest('examDescriptionSelectField', 'examDescription.selectField', ["examDescriptions"=>$examDescriptions, "selectedExamDescription"=>0])
             ->nest('classroomSelectField', 'classroom.selectField', ["classrooms"=>$classrooms, "selectedClassroom"=>0]);
    }

    public function orderBy($column)
    {
        if(session()->get('TermOrderBy[0]') == $column)
          if(session()->get('TermOrderBy[1]') == 'desc')
            session()->put('TermOrderBy[1]', 'asc');
          else
            session()->put('TermOrderBy[1]', 'desc');
        else
        {
          session()->put('TermOrderBy[4]', session()->get('TermOrderBy[2]'));
          session()->put('TermOrderBy[2]', session()->get('TermOrderBy[0]'));
          session()->put('TermOrderBy[0]', $column);
          session()->put('TermOrderBy[5]', session()->get('TermOrderBy[3]'));
          session()->put('TermOrderBy[3]', session()->get('TermOrderBy[1]'));
          session()->put('TermOrderBy[1]', 'asc');
        }
        return redirect( route('termin.index') );
    }

    public function create(ExamDescriptionRepository $examDescriptionRepo, ClassroomRepository $classroomRepo)
    {
        $examDescriptions = $examDescriptionRepo->getAll();
        $classrooms = $classroomRepo->getAll();
        return view('term.create')
             ->nest('examDescriptionSelectField', 'examDescription.selectField', ["examDescriptions"=>$examDescriptions, "selectedExamDescription"=>0])
             ->nest('classroomSelectField', 'classroom.selectField', ["classrooms"=>$classrooms, "selectedClassroom"=>0]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
          'exam_description_id' => 'required',
          'date_start' => 'required',
          'date_end' => 'required',
        ]);

        $term = new Term;
        $term->exam_description_id = $request->exam_description_id;
        $term->classroom_id = $request->classroom_id;
        $term->date_start = $request->date_start;
        $term->date_end = $request->date_end;
        $term->save();

        return redirect($request->history_view);
    }

    public function show($id, $view='', TermRepository $termRepo)
    {
        if(empty(session()->get('termView')))  session()->put('termView', 'showInfo');
        if($view)  session()->put('termView', $view);
        $term = $termRepo -> find($id);
        $previous = $termRepo -> PreviousRecordId($id);
        $next = $termRepo -> NextRecordId($id);

        switch(session()->get('termView')) {
             case 'showInfo':
               return view('term.showInfo', ["term"=>$term, "previous"=>$previous, "next"=>$next]);
               exit;
             break;
             case 'showExams':
               //$studentClasses = StudentClass::all() -> where('grade_id', $id);
               return view('term.showExams', ["term"=>$term, "previous"=>$previous, "next"=>$next]);
               exit;
             break;
             default:
               printf('<p style="background: #bb0; color: #f00; font-size: x-large; text-align: center; border: 3px solid red; padding: 5px;">Widok %s nieznany</p>', $view);
               exit;
             break;
        }
    }

    public function edit(Term $termin, ExamDescriptionRepository $examDescriptionRepo, ClassroomRepository $classroomRepo)
    {
        $examDescriptions = $examDescriptionRepo->getAll();
        $classrooms = $classroomRepo->getAll();
        return view('term.edit', ["term"=>$termin])
             ->nest('examDescriptionSelectField', 'examDescription.selectField', ["examDescriptions"=>$examDescriptions, "selectedExamDescription"=>$termin->exam_description_id])
             ->nest('classroomSelectField', 'classroom.selectField', ["classrooms"=>$classrooms, "selectedClassroom"=>$termin->classroom_id]);
    }

    public function update(Request $request, Term $termin)
    {
        $this->validate($request, [
          'exam_description_id' => 'required',
          'date_start' => 'required',
          'date_end' => 'required',
        ]);

        $termin->exam_description_id = $request->exam_description_id;
        $termin->classroom_id = $request->classroom_id;
        $termin->date_start = $request->date_start;
        $termin->date_end = $request->date_end;
        $termin->save();

        return redirect($request->history_view);
    }

    public function destroy(Term $termin)
    {
        $termin->delete();
        return redirect( $_SERVER['HTTP_REFERER'] );
    }
*/
}
