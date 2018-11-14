<?php
namespace App\Http\Controllers;
//use App\Models\Exam;
//use App\Repositories\ExamRepository;
//use App\Repositories\DeclarationRepository;
//use App\Repositories\ExamDescriptionRepository;
//use App\Repositories\TermRepository;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    public function index(ExamRepository $examRepo, DeclarationRepository $declarationRepo, ExamDescriptionRepository $examDescriptionRepo, TermRepository $termRepo)
    {
        for($i=0; $i<6; $i++)
          $orderBy[$i] = session()->get("ExamOrderBy[$i]");

        $exams = $examRepo->getAll($orderBy);
        $declarations = $declarationRepo->getAll();
        $examDescriptions = $examDescriptionRepo->getAll();
        $terms = $termRepo->getAll();
        $examTypes = array('obowiązkowy', 'dodatkowy');
        $selectedExamType = '';
        return view('exam.index', ["exams"=>$exams])
             ->nest('declarationSelectField', 'declaration.selectField', ["declarations"=>$declarations, "selectedDeclaration"=>0])
             ->nest('examDescriptionSelectField', 'examDescription.selectField', ["examDescriptions"=>$examDescriptions, "selectedExamDescription"=>0])
             ->nest('termSelectField', 'term.selectField', ["terms"=>$terms, "selectedTerm"=>0])
             ->nest('examTypeSelectField', 'exam.ExamTypeSelectField', ["examTypes"=>$examTypes, "selectedExamType"=>$selectedExamType]);
    }

    public function orderBy($column)
    {
        if(session()->get('ExamOrderBy[0]') == $column)
          if(session()->get('ExamOrderBy[1]') == 'desc')
            session()->put('ExamOrderBy[1]', 'asc');
          else
            session()->put('ExamOrderBy[1]', 'desc');
        else
        {
          session()->put('ExamOrderBy[4]', session()->get('ExamOrderBy[2]'));
          session()->put('ExamOrderBy[2]', session()->get('ExamOrderBy[0]'));
          session()->put('ExamOrderBy[0]', $column);
          session()->put('ExamOrderBy[5]', session()->get('ExamOrderBy[3]'));
          session()->put('ExamOrderBy[3]', session()->get('ExamOrderBy[1]'));
          session()->put('ExamOrderBy[1]', 'asc');
        }
        return redirect( route('egzamin.index') );
    }

    public function create(DeclarationRepository $declarationRepo, ExamDescriptionRepository $examDescriptionRepo, TermRepository $termRepo)
    {
        $declarations = $declarationRepo->getAll();
        $examDescriptions = $examDescriptionRepo->getAll();
        $terms = $termRepo->getAll();
        $examTypes = array('obowiązkowy', 'dodatkowy');
        $selectedExamType = 'obowiązkowy';
        return view('exam.create')
             ->nest('declarationSelectField', 'declaration.selectField', ["declarations"=>$declarations, "selectedDeclaration"=>0])
             ->nest('examDescriptionSelectField', 'examDescription.selectField', ["examDescriptions"=>$examDescriptions, "selectedExamDescription"=>0])
             ->nest('termSelectField', 'term.selectField', ["terms"=>$terms, "selectedTerm"=>0])
             ->nest('examTypeSelectField', 'exam.ExamTypeSelectField', ["examTypes"=>$examTypes, "selectedExamType"=>$selectedExamType]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
          'declaration_id' => 'required',
          'exam_description_id' => 'required',
          'exam_type' => 'required',
          'points' => 'numeric',
          'comments' => 'max:15',
        ]);

        $exam = new Exam;
        $exam->declaration_id = $request->declaration_id;
        $exam->exam_description_id = $request->exam_description_id;
        $exam->term_id = $request->term_id;
        $exam->exam_type = $request->exam_type;
        $exam->points = $request->points;
        $exam->comments = $request->comments;
        $exam->save();

        return redirect($request->history_view);
    }

    public function show(Exam $egzamin, ExamRepository $examRepo)
    {
        $previous = $examRepo->previousRecordId($egzamin->id);
        $next = $examRepo->nextRecordId($egzamin->id);
        return view('exam.show', ["exam"=>$egzamin, "previous"=>$previous, "next"=>$next]);
    }

    public function edit(Exam $egzamin, DeclarationRepository $declarationRepo, ExamDescriptionRepository $examDescriptionRepo, TermRepository $termRepo)
    {
       $declarations = $declarationRepo->getAll();
       $examDescriptions = $examDescriptionRepo->getAll();
       $terms = $termRepo->getAll();
       $examTypes = array('obowiązkowy', 'dodatkowy');
       return view('exam.edit', ["exam"=>$egzamin])
            ->nest('declarationSelectField', 'declaration.selectField', ["declarations"=>$declarations, "selectedDeclaration"=>$egzamin->declaration_id])
            ->nest('examDescriptionSelectField', 'examDescription.selectField', ["examDescriptions"=>$examDescriptions, "selectedExamDescription"=>$egzamin->exam_description_id])
            ->nest('termSelectField', 'term.selectField', ["terms"=>$terms, "selectedTerm"=>$egzamin->term_id])
            ->nest('examTypeSelectField', 'exam.ExamTypeSelectField', ["examTypes"=>$examTypes, "selectedExamType"=>$egzamin->exam_type]);
    }

    public function update(Request $request, Exam $egzamin)
    {
        $this->validate($request, [
          'declaration_id' => 'required',
          'exam_description_id' => 'required',
          'exam_type' => 'required',
          'points' => 'numeric',
          'comments' => 'max:15',
        ]);

        $egzamin->declaration_id = $request->declaration_id;
        $egzamin->exam_description_id = $request->exam_description_id;
        $egzamin->term_id = $request->term_id;
        $egzamin->exam_type = $request->exam_type;
        $egzamin->points = $request->points;
        $egzamin->comments = $request->comments;
        $egzamin->save();

        return redirect($request->history_view);
    }

    public function destroy(Exam $egzamin)
    {
        $egzamin->delete();
        return redirect( $_SERVER['HTTP_REFERER'] );
    }
}
