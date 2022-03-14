<?php
// ------------------------ (C) mgr inż. Bartłomiej Trojnar; 24.11.2021 ------------------------ //
namespace App\Http\Controllers;
use App\Models\Exam;

use App\Repositories\DeclarationRepository;
use App\Repositories\ExamRepository;
use App\Repositories\ExamDescriptionRepository;
use App\Repositories\TermRepository;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    public function orderBy($column) {
        if(session()->get('ExamOrderBy[0]') == $column)
            if(session()->get('ExamOrderBy[1]') == 'desc')  session()->put('ExamOrderBy[1]', 'asc');
            else  session()->put('ExamOrderBy[1]', 'desc');
        else {
            session()->put('ExamOrderBy[4]', session()->get('ExamOrderBy[2]'));
            session()->put('ExamOrderBy[2]', session()->get('ExamOrderBy[0]'));
            session()->put('ExamOrderBy[0]', $column);
            session()->put('ExamOrderBy[5]', session()->get('ExamOrderBy[3]'));
            session()->put('ExamOrderBy[3]', session()->get('ExamOrderBy[1]'));
            session()->put('ExamOrderBy[1]', 'asc');
        }
        return redirect( $_SERVER['HTTP_REFERER'] );
    }

    public function create(Request $request, DeclarationRepository $declarationRepo, ExamDescriptionRepository $examDescriptionRepo, TermRepository $termRepo) {
        if($request->version=="manyExamsForDeclaration")    return $this -> createManyExamsForDeclaration($request->declaration_id, $declarationRepo, $examDescriptionRepo);
        $examTypes = array('obowiązkowy', 'dodatkowy');
        $examTypeSelected = 'obowiązkowy';
        $examTypeSelectField = view('examDescription.examTypeSelectField', ["examTypes"=>$examTypes, "examTypeSelected"=>$examTypeSelected]);

        if($request->version=="forExamDescription")
            return $this -> createForExamDescription($request->exam_description_id, $examDescriptionRepo, $declarationRepo, $termRepo, $examTypeSelectField);

        $Declaration = $declarationRepo -> find($request->declaration_id);
        $session_id = $Declaration->session_id;
        if($request->version=="forStudentDeclaration")
            return $this -> createForStudentDeclaration($Declaration, $session_id, $examDescriptionRepo, $termRepo, $examTypeSelectField);

        return $this -> createForDeclaration($request->declaration_id, $declarationRepo, $examDescriptionRepo, $termRepo, $examTypeSelectField);
    }

    private function createManyExamsForDeclaration($declaration_id, $declarationRepo, $examDescriptionRepo) {
        $declaration = $declarationRepo -> find($declaration_id);
        $subject_id = session()->get('subjectSelected');
        $session_id = $declaration->session_id;
        $exam_type = session()->get('typeSelected');
        $level = session()->get('levelSelected');
        $examDescriptions = $examDescriptionRepo -> getFilteredAndSorted($subject_id, $session_id, $exam_type, $level);
        return view('exam.createManyForDeclaration', ["declaration_id"=>$declaration_id, "examDescriptions"=>$examDescriptions]);
    }

    private function createForDeclaration($declaration_id, $declarationRepo, $examDescriptionRepo, $termRepo, $examTypeSelectField) {
        $declaration = $declarationRepo -> find($declaration_id);
        $session_id = $declaration->session_id;
        $subject_id = session()->get('subjectSelected');
        $exam_type = session()->get('typeSelected');
        $level = session()->get('levelSelected');
        $examDescriptions = $examDescriptionRepo -> getFilteredAndSorted($subject_id, $session_id, $exam_type, $level);
        $examDescriptionSelected = session()->get('examDescriptionSelected');
        $exam_description = view('examDescription.selectField', ["examDescriptions"=>$examDescriptions, "examDescriptionSelected"=>$examDescriptionSelected]);
        $classroom_id = session()->get('classroomSelected');
        $terms = $termRepo -> getFilteredAndSorted($session_id, $examDescriptionSelected, $classroom_id);
        $termSelectField = view('term.selectField', ["terms"=>$terms, "termSelected"=>session()->get('termSelected')]);
        return view('exam.create', ["version"=>"forDeclaration", "exam_description"=>$exam_description, "declaration"=>$declaration_id, "termSelectField"=>$termSelectField, "examTypeSelectField"=>$examTypeSelectField]);
    }

    private function createForExamDescription($exam_description_id, $examDescriptionRepo, $declarationRepo, $termRepo, $examTypeSelectField) {
        $exam_description = $examDescriptionRepo -> find($exam_description_id);
        $session_id = $exam_description->session_id;
        $declarations = $declarationRepo -> getFilteredAndSorted($session_id);
        $declaration = view('declaration.selectField', ["declarations"=>$declarations, "declarationSelected"=>session()->get('declarationSelected')]);
        $classroom_id = session()->get('classroomSelected');
        $terms = $termRepo -> getFilteredAndSorted($session_id, $exam_description_id, $classroom_id);
        $termSelectField = view('term.selectField', ["terms"=>$terms, "termSelected"=>session()->get('termSelected')]);
        return view('exam.create', ["version"=>"forExamDescription", "exam_description"=>$exam_description_id, "declaration"=>$declaration, "termSelectField"=>$termSelectField, "examTypeSelectField"=>$examTypeSelectField]);
    }

    private function createForStudentDeclaration($Declaration, $session_id, $examDescriptionRepo, $termSelectField, $examTypeSelectField) {
        $subject_id = session()->get('subjectSelected');
        $exam_type = session()->get('typeSelected');
        $level = session()->get('levelSelected');
        $examDescriptions = $examDescriptionRepo -> getFilteredAndSorted($subject_id, $session_id, $exam_type, $level);
        $exam_description = view('examDescription.selectField', ["examDescriptions"=>$examDescriptions, "examDescriptionSelected"=>0]);
        $declaration = $Declaration->id;
        return view('exam.createForStudentDeclaration', ["exam_description"=>$exam_description, "declaration"=>$declaration, "termSelectField"=>$termSelectField, "examTypeSelectField"=>$examTypeSelectField]);
    }

    public function store(Request $request) {
        $this -> validate($request, [
          'declaration_id' => 'required',
          'exam_description_id' => 'required',
          'type' => 'required',
          'points' => 'numeric',
          'comments' => 'max:15',
        ]);

        $exam = new Exam;
        $exam->declaration_id = $request->declaration_id;
        $exam->exam_description_id = $request->exam_description_id;
        if($request->term_id) $exam->term_id = $request->term_id;
        else $exam->term_id = NULL;
        $exam->type = $request->type;
        $exam->points = $request->points;
        if($request->points=="")    $exam->points = NULL;
        $exam->comments = $request->comments;
        $exam -> save();

        return $exam->id;
    }

    public function edit(Request $request, ExamRepository $examRepo, DeclarationRepository $declarationRepo, ExamDescriptionRepository $examDescriptionRepo, TermRepository $termRepo) {
        $exam = $examRepo -> find($request->id);
        $session_id = $exam->declaration->session_id;

        $declarations = $declarationRepo -> getFilteredAndSorted($session_id, 0, 0);
        $declaration = view('declaration.selectField', ["declarations"=>$declarations, "declarationSelected"=>$exam->declaration_id]);
        $classroom_id = session()->get('classroomSelected');
        $terms = $termRepo -> getFilteredAndSorted($session_id, $exam->exam_description_id, $classroom_id);
        $termSelectField = view('term.selectField', ["terms"=>$terms, "termSelected"=>$exam->term_id]);
        $examTypes = array('obowiązkowy', 'dodatkowy');
        $examTypeSelectField = view('examDescription.examTypeSelectField', ["examTypes"=>$examTypes, "examTypeSelected"=>$exam->type]);

        if($request->version=="forStudentDeclaration") {
            $subject_id = session()->get('subjectSelected');
            $exam_type = session()->get('typeSelected');
            $level = session()->get('levelSelected');
            $examDescriptions = $examDescriptionRepo -> getFilteredAndSorted($subject_id, $session_id, $exam_type, $level);
            $exam_description = view('examDescription.selectField', ["examDescriptions"=>$examDescriptions, "examDescriptionSelected"=>$exam->exam_description_id]);
            return view('exam.editForStudentDeclaration', ["exam"=>$exam, "version"=>$request->version, "declaration"=>$declaration, "termSelectField"=>$termSelectField, "examTypeSelectField"=>$examTypeSelectField, "exam_description"=>$exam_description]);
        }
        if($request->version=="forDeclaration") {
            $subject_id = session()->get('subjectSelected');
            $exam_type = session()->get('typeSelected');
            $level = session()->get('levelSelected');
            $examDescriptions = $examDescriptionRepo -> getFilteredAndSorted($subject_id, $session_id, $exam_type, $level);
            $exam_description = view('examDescription.selectField', ["examDescriptions"=>$examDescriptions, "examDescriptionSelected"=>$exam->exam_description_id]);
        }
        if($request->version=="forExamDescription") {
            $exam_description = $exam->exam_description_id;
        }

        return view('exam.edit', ["exam"=>$exam, "lp"=>$request->lp, "version"=>$request->version, "declaration"=>$declaration, "termSelectField"=>$termSelectField, "examTypeSelectField"=>$examTypeSelectField, "exam_description"=>$exam_description]);
    }

    public function update(Request $request, $id, Exam $exam) {
        $exam = $exam -> find($id);
        $this -> validate($request, [
          'declaration_id' => 'required',
          'exam_description_id' => 'required',
          'type' => 'required',
          'points' => 'numeric',
          'comments' => 'max:15',
        ]);

        $exam->declaration_id = $request->declaration_id;
        $exam->exam_description_id = $request->exam_description_id;
        if($request->term_id) $exam->term_id = $request->term_id;
        else $exam->term_id = NULL;
        $exam->type = $request->type;
        $exam->points = $request->points;
        if($request->points=="")    $exam->points = NULL;
        $exam->comments = $request->comments;
        $exam -> save();

        return $exam->id;
    }

    public function destroy($id, Exam $exam) {
        $exam = $exam -> find($id);
        $exam -> delete();
        return 1;
    }

    public function refreshRow(Request $request, ExamRepository $examRepo) {
        $exam = $examRepo -> find($request->exam_id);
        if($request->version == "forStudentDeclaration")    return view('exam.rowForStudentDeclaration', ["exam"=>$exam]);
        return view('exam.row', ["exam"=>$exam, "version"=>$request->version, "lp"=>$request->lp]);
    }
}