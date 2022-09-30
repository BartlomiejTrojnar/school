<?php
// ------------------------ (C) mgr inż. Bartłomiej Trojnar; 30.09.2022 ------------------------ //
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
        //widok dla opisu egzaminu
        if($request->version=="forExamDescription") {
            $examDescription = $examDescriptionRepo -> find($request->exam_description_id);
            return $this -> createForExamDescription($examDescription, $declarationRepo, $termRepo);
        }

        $Declaration = $declarationRepo -> find($request->declaration_id);
        return $this -> createForDeclaration($request->version, $Declaration, $examDescriptionRepo, $termRepo);
    }

    private function createForDeclaration($version, $Declaration, $examDescriptionRepo, $termRepo) {
        $subject_id = session()->get('subjectSelected');
        $session_id = $Declaration->session_id;
        $exam_type = session()->get('typeSelected');
        $level = session()->get('levelSelected');
        $examDescriptions = $examDescriptionRepo -> getFilteredAndSorted($subject_id, $session_id, $exam_type, $level);
        $examDescriptionSelected = session()->get('examDescriptionSelected');
        $examDescriptionSF = view('examDescription.selectField', ["examDescriptions"=>$examDescriptions, "examDescriptionSelected"=>$examDescriptionSelected]);
        $examTypes = array('obowiązkowy', 'dodatkowy');
        $examTypeSF = view('examDescription.examTypeSelectField', ["examTypes"=>$examTypes, "examTypeSelected"=>'obowiązkowy']);
        $classroom_id = session()->get('classroomSelected');
        $terms = $termRepo -> getFilteredAndSorted($session_id, $examDescriptionSelected, $classroom_id);
        $termSF = view('term.selectField', ["terms"=>$terms, "termSelected"=>session()->get('termSelected')]);

        //widok dla dodawania wielu egzaminów dla deklaracji
        if($version=="manyExamsForDeclaration")
            return view('exam.createManyForDeclaration', ["declaration_id"=>$Declaration->id, "examDescriptions"=>$examDescriptions]);
        //widok dla deklaracji ucznia
        if($version=="forStudentDeclaration")
            return view('exam.createForStudentDeclaration', ["declarationID"=>$Declaration->id, "examDescriptionSF"=>$examDescriptionSF, "examTypeSF"=>$examTypeSF, "termSF"=>$termSF]);
        //widok dla deklaracji
        return view('exam.create', ["version"=>"forDeclaration", "declarationID"=>$Declaration->id, "examDescriptionSF"=>$examDescriptionSF, "termSF"=>$termSF, "examTypeSF"=>$examTypeSF]);
    }

    private function createForExamDescription($examDescription, $declarationRepo, $termRepo) {
        $session_id = $examDescription->session_id;
        $declarations = $declarationRepo -> getFilteredAndSorted($session_id);
        $declarationSF = view('declaration.selectField', ["declarations"=>$declarations, "declarationSelected"=>session()->get('declarationSelected')]);
        $classroom_id = session()->get('classroomSelected');
        $terms = $termRepo -> getFilteredAndSorted($session_id, $examDescription->id, $classroom_id);
        $termSF = view('term.selectField', ["terms"=>$terms, "termSelected"=>session()->get('termSelected')]);
        $examTypes = array('obowiązkowy', 'dodatkowy');
        $examTypeSF = view('examDescription.examTypeSelectField', ["examTypes"=>$examTypes, "examTypeSelected"=>'obowiązkowy']);
        return view('exam.create', ["version"=>"forExamDescription", "examDescriptionID"=>$examDescription->id, "declarationSF"=>$declarationSF, "termSF"=>$termSF, "examTypeSF"=>$examTypeSF]);
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

    public function addExamsForDeclaration(Request $request, ExamDescriptionRepository $examDescriptionRepo) {
        $n = count($request->examsDN);
        for($i=0; $i<$n; $i++) {
            $exam = new Exam;
            $exam->declaration_id = $request->declaration_id;
            $exam->exam_description_id = $request->examsDN[$i];
            $eDN = $examDescriptionRepo -> find($request->examsDN[$i]);
            $exam->type = 2;
            if($eDN->subject_id==3 && $eDN->level=='podstawowy') $exam->type = 1;
            if($eDN->subject_id==4 && $eDN->level=='podstawowy') $exam->type = 1;
            if($eDN->subject_id==12 && $eDN->level=='podstawowy') $exam->type = 1;
            if($eDN->subject_id==3 && $eDN->type=='ustny') $exam->type = 1;
            if($eDN->subject_id==4 && $eDN->type=='ustny') $exam->type = 1;
            $exam->term_id = NULL;
            $exam->points = NULL;
            $exam->comments = NULL;
            $exam -> save();
        }
    }

    public function edit(Request $request, ExamRepository $examRepo, DeclarationRepository $declarationRepo, ExamDescriptionRepository $examDescriptionRepo, TermRepository $termRepo) {
        $exam = $examRepo -> find($request->id);
        $session_id = $exam->declaration->session_id;

        $declarations = $declarationRepo -> getFilteredAndSorted($session_id, 0, 0);
        $declaration = view('declaration.selectField', ["declarations"=>$declarations, "declarationSelected"=>$exam->declaration_id]);
        $classroom_id = session()->get('classroomSelected');
        $terms = $termRepo -> getFilteredAndSorted($session_id, $exam->exam_description_id, $classroom_id);
        $termSF = view('term.selectField', ["terms"=>$terms, "termSelected"=>$exam->term_id]);
        $examTypes = array('obowiązkowy', 'dodatkowy');
        $examTypeSF = view('examDescription.examTypeSelectField', ["examTypes"=>$examTypes, "examTypeSelected"=>$exam->type]);

        if($request->version=="forStudentDeclaration") {
            $subject_id = session()->get('subjectSelected');
            $exam_type = session()->get('typeSelected');
            $level = session()->get('levelSelected');
            $examDescriptions = $examDescriptionRepo -> getFilteredAndSorted($subject_id, $session_id, $exam_type, $level);
            $exam_description = view('examDescription.selectField', ["examDescriptions"=>$examDescriptions, "examDescriptionSelected"=>$exam->exam_description_id]);
            return view('exam.editForStudentDeclaration', ["exam"=>$exam, "version"=>$request->version, "declaration"=>$declaration, "termSF"=>$termSF, "examTypeSF"=>$examTypeSF, "exam_description"=>$exam_description]);
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

        return view('exam.edit', ["exam"=>$exam, "lp"=>$request->lp, "version"=>$request->version, "declaration"=>$declaration, "termSF"=>$termSF, "examTypeSF"=>$examTypeSF, "exam_description"=>$exam_description]);
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