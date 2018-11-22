<?php
namespace App\Http\Controllers;
//use App\Models\Enlargement;
//use App\Repositories\EnlargementRepository;
//use App\Repositories\StudentRepository;
//use App\Repositories\SubjectRepository;
use Illuminate\Http\Request;

class EnlargementController extends Controller
{
/*
    public function index(EnlargementRepository $enlargementRepo)
    {
        for($i=0; $i<6; $i++)
          $orderBy[$i] = session()->get("EnlargementOrderBy[$i]");

        $enlargements = $enlargementRepo->getAll($orderBy);
        return view('enlargement.index', ["enlargements"=>$enlargements]);
    }

    public function orderBy($column)
    {
        if(session()->get('EnlargementOrderBy[0]') == $column)
          if(session()->get('EnlargementOrderBy[1]') == 'desc')
            session()->put('EnlargementOrderBy[1]', 'asc');
          else
            session()->put('EnlargementOrderBy[1]', 'desc');
        else
        {
          session()->put('EnlargementOrderBy[4]', session()->get('EnlargementOrderBy[2]'));
          session()->put('EnlargementOrderBy[2]', session()->get('EnlargementOrderBy[0]'));
          session()->put('EnlargementOrderBy[0]', $column);
          session()->put('EnlargementOrderBy[5]', session()->get('EnlargementOrderBy[3]'));
          session()->put('EnlargementOrderBy[3]', session()->get('EnlargementOrderBy[1]'));
          session()->put('EnlargementOrderBy[1]', 'asc');
        }
        return redirect( route('rozszerzenie.index') );
    }

    public function create(SubjectRepository $subjectRepo, StudentRepository $studentRepo)
    {
        $subjects = $subjectRepo->getAll();
        $students = $studentRepo->getAll();
        $levels = array('IV.0', 'IV.1p.', 'IV.1r.');
        return view('enlargement.create')
             ->nest('subjectSelectField', 'subject.selectField', ["subjects"=>$subjects, "selectedSubject"=>0])
             ->nest('studentSelectField', 'student.selectField', ["students"=>$students, "selectedStudent"=>0])
             ->nest('levelSelectField', 'layouts.levelSelectField', ["levels"=>$levels, "selectedLevel"=>'']);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
          'student_id' => 'required',
          'subject_id' => 'required',
          'date_of_choice' => 'required|date',
          'date_of_resignation' => 'date',
        ]);

        $enlargement = new Enlargement;
        $enlargement->student_id = $request->student_id;
        $enlargement->subject_id = $request->subject_id;
        $enlargement->language_level = $request->language_level;
        $enlargement->date_of_choice = $request->date_of_choice;
        $enlargement->date_of_resignation = $request->date_of_resignation;
        $enlargement->save();

        return redirect($request->history_view);
    }

    public function show(Enlargement $rozszerzenie, EnlargementRepository $enlargementRepo)
    {
        $previous = $enlargementRepo->previousRecordId($rozszerzenie->id);
        $next = $enlargementRepo->nextRecordId($rozszerzenie->id);
        return view('enlargement.show', ["enlargement"=>$rozszerzenie, "previous"=>$previous, "next"=>$next]);
    }

    public function edit(Enlargement $rozszerzenie, SubjectRepository $subjectRepo, StudentRepository $studentRepo)
    {
        $subjects = $subjectRepo->getAll();
        $students = $studentRepo->getAll();
        $levels = array('IV.0', 'IV.1p.', 'IV.1r.');
        return view('enlargement.edit', ["enlargement"=>$rozszerzenie])
             ->nest('subjectSelectField', 'subject.selectField', ["subjects"=>$subjects, "selectedSubject"=>$rozszerzenie->subject_id])
             ->nest('studentSelectField', 'student.selectField', ["students"=>$students, "selectedStudent"=>$rozszerzenie->student_id])
             ->nest('levelSelectField', 'layouts.levelSelectField', ["levels"=>$levels, "selectedLevel"=>$rozszerzenie->level]);
    }

    public function update(Request $request, Enlargement $rozszerzenie)
    {
        $this->validate($request, [
          'student_id' => 'required',
          'subject_id' => 'required',
          'date_of_choice' => 'required|date',
          'date_of_resignation' => 'nullable|date',
        ]);

        $rozszerzenie->student_id = $request->student_id;
        $rozszerzenie->subject_id = $request->subject_id;
        $rozszerzenie->language_level = $request->language_level;
        $rozszerzenie->date_of_choice = $request->date_of_choice;
        $rozszerzenie->date_of_resignation = $request->date_of_resignation;
        $rozszerzenie->save();

        return redirect($request->history_view);
    }

    public function destroy(Enlargement $rozszerzenie)
    {
        $rozszerzenie->delete();
        return redirect( $_SERVER['HTTP_REFERER'] );
    }
*/
}
