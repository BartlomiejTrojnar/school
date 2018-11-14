<?php
namespace App\Http\Controllers;
//use App\Models\ExamDescription;
//use App\Repositories\ExamDescriptionRepository;
//use App\Repositories\SessionRepository;
//use App\Repositories\SubjectRepository;
use Illuminate\Http\Request;

class ExamDescriptionController extends Controller
{
    public function index(ExamDescriptionRepository $examDescriptionRepo, SessionRepository $sessionRepo, SubjectRepository $subjectRepo)
    {
        for($i=0; $i<6; $i++)
          $orderBy[$i] = session()->get("ExamDescriptionOrderBy[$i]");

        $examDescriptions = $examDescriptionRepo->getAll($orderBy);
        $sessions = $sessionRepo->getAll();
        $subjects = $subjectRepo->getAll();
        $examTypes = array('pisemny', 'ustny');
        $selectedExamType = '';
        $levels = array('podstawowy', 'rozszerzony', 'nieokreślony');
        $selectedLevel = '';
        return view('examDescription.index', ["examDescriptions"=>$examDescriptions])
             ->nest('sessionSelectField', 'session.selectField', ["sessions"=>$sessions, "selectedSession"=>0])
             ->nest('subjectSelectField', 'subject.selectField', ["subjects"=>$subjects, "selectedSubject"=>0])
             ->nest('examTypeSelectField', 'examDescription.examTypeSelectField', ["examTypes"=>$examTypes, "selectedExamType"=>$selectedExamType])
             ->nest('levelSelectField', 'layouts.levelSelectField', ["levels"=>$levels, "selectedLevel"=>$selectedLevel]);
    }

    public function orderBy($column)
    {
        if(session()->get('ExamDescriptionOrderBy[0]') == $column)
          if(session()->get('ExamDescriptionOrderBy[1]') == 'desc')
            session()->put('ExamDescriptionOrderBy[1]', 'asc');
          else
            session()->put('ExamDescriptionOrderBy[1]', 'desc');
        else
        {
          session()->put('ExamDescriptionOrderBy[4]', session()->get('ExamDescriptionOrderBy[2]'));
          session()->put('ExamDescriptionOrderBy[2]', session()->get('ExamDescriptionOrderBy[0]'));
          session()->put('ExamDescriptionOrderBy[0]', $column);
          session()->put('ExamDescriptionOrderBy[5]', session()->get('ExamDescriptionOrderBy[3]'));
          session()->put('ExamDescriptionOrderBy[3]', session()->get('ExamDescriptionOrderBy[1]'));
          session()->put('ExamDescriptionOrderBy[1]', 'asc');
        }
        return redirect( route('opis_egzaminu.index') );
    }

    public function create(SessionRepository $sessionRepo, SubjectRepository $subjectRepo)
    {
        $sessions = $sessionRepo->getAll();
        $subjects = $subjectRepo->getAll();
        $examTypes = array('pisemny', 'ustny');
        $selectedExamType = 'pisemny';
        $levels = array('podstawowy', 'rozszerzony', 'nieokreślony');
        $selectedLevel = 'podstawowy';
        return view('examDescription.create')
             ->nest('sessionSelectField', 'session.selectField', ["sessions"=>$sessions, "selectedSession"=>0])
             ->nest('subjectSelectField', 'subject.selectField', ["subjects"=>$subjects, "selectedSubject"=>0])
             ->nest('examTypeSelectField', 'examDescription.examTypeSelectField', ["examTypes"=>$examTypes, "selectedExamType"=>$selectedExamType])
             ->nest('levelSelectField', 'layouts.levelSelectField', ["levels"=>$levels, "selectedLevel"=>$selectedLevel]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
          'session_id' => 'required',
          'subject_id' => 'required',
          'exam_type' => 'required',
          'level' => 'required',
          'max_points' => 'required|integer|max:100',
        ]);

        $examDescription = new ExamDescription;
        $examDescription->session_id = $request->session_id;
        $examDescription->subject_id = $request->subject_id;
        $examDescription->type = $request->exam_type;
        $examDescription->level = $request->level;
        $examDescription->max_points = $request->max_points;
        $examDescription->save();

        return redirect($request->history_view);
    }

    public function show(ExamDescription $opis_egzaminu, ExamDescriptionRepository $examDescriptionRepo)
    {
        $previous = $examDescriptionRepo->previousRecordId($opis_egzaminu->id);
        $next = $examDescriptionRepo->nextRecordId($opis_egzaminu->id);
        return view('examDescription.show', ["examDescription"=>$opis_egzaminu, "previous"=>$previous, "next"=>$next]);
    }

    public function edit(ExamDescription $opis_egzaminu, SessionRepository $sessionRepo, SubjectRepository $subjectRepo)
    {
        $sessions = $sessionRepo->getAll();
        $subjects = $subjectRepo->getAll();
        $examTypes = array('pisemny', 'ustny');
        $levels = array('podstawowy', 'rozszerzony', 'nieokreślony');
        return view('examDescription.edit', ["examDescription"=>$opis_egzaminu])
             ->nest('sessionSelectField', 'session.selectField', ["sessions"=>$sessions, "selectedSession"=>$opis_egzaminu->session_id])
             ->nest('subjectSelectField', 'subject.selectField', ["subjects"=>$subjects, "selectedSubject"=>$opis_egzaminu->subject_id])
             ->nest('examTypeSelectField', 'examDescription.examTypeSelectField', ["examTypes"=>$examTypes, "selectedExamType"=>$opis_egzaminu->type])
             ->nest('levelSelectField', 'layouts.levelSelectField', ["levels"=>$levels, "selectedLevel"=>$opis_egzaminu->level]);
    }

    public function update(Request $request, ExamDescription $opis_egzaminu)
    {
        $this->validate($request, [
          'session_id' => 'required',
          'subject_id' => 'required',
          'exam_type' => 'required',
          'level' => 'required',
          'max_points' => 'required|integer|max:100',
        ]);

        $opis_egzaminu->session_id = $request->session_id;
        $opis_egzaminu->subject_id = $request->subject_id;
        $opis_egzaminu->type = $request->exam_type;
        $opis_egzaminu->level = $request->level;
        $opis_egzaminu->max_points = $request->max_points;
        $opis_egzaminu->save();

        return redirect($request->history_view);
    }

    public function destroy(ExamDescription $opis_egzaminu)
    {
        $opis_egzaminu->delete();
        return redirect( $_SERVER['HTTP_REFERER'] );
    }
}
