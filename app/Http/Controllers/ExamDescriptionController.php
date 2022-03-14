<?php
// ------------------------ (C) mgr inż. Bartłomiej Trojnar; 20.11.2021 ------------------------ //
namespace App\Http\Controllers;
use App\Models\ExamDescription;
use App\Repositories\ExamDescriptionRepository;

use App\Repositories\ClassroomRepository;
use App\Repositories\DeclarationRepository;
use App\Repositories\ExamRepository;
use App\Repositories\SessionRepository;
use App\Repositories\SubjectRepository;
use App\Repositories\TermRepository;
use Illuminate\Http\Request;

class ExamDescriptionController extends Controller 
{
    public function index(ExamDescriptionRepository $examDescriptionRepo, SubjectRepository $subjectRepo, SessionRepository $sessionRepo) {
        $subjects = $subjectRepo -> getActualAndSorted();
        $subjectSelected = session()->get('subjectSelected');
        $subjectSelectField = view('subject.selectField', ["subjects"=>$subjects, "subjectSelected"=>$subjectSelected]);

        $sessions = $sessionRepo -> getAllSorted();
        $sessionSelected = session()->get('sessionSelected');
        $sessionSelectField = view('session.selectField', ["sessions"=>$sessions, "sessionSelected"=>$sessionSelected]);

        $examTypes = array('pisemny', 'ustny');
        $examTypeSelected = session()->get('typeSelected');
        $examTypeSelectField = view('examDescription.examTypeSelectField', ["examTypes"=>$examTypes, "examTypeSelected"=>$examTypeSelected]);

        $levels = array('rozszerzony', 'podstawowy', 'nieustalony', 'dwujęzyczny');
        $levelSelected = session()->get('levelSelected');
        $levelSelectField = view('layouts.levelSelectField', ["levels"=>$levels, "levelSelected"=>$levelSelected]);

        $examDescriptions = $examDescriptionRepo -> getFilteredAndSortedAndPaginate($subjectSelected, $sessionSelected, $examTypeSelected, $levelSelected);
        return view('examDescription.index', ["examDescriptions"=>$examDescriptions, "sessionSelectField"=>$sessionSelectField, "subjectSelectField"=>$subjectSelectField, "examTypeSelectField"=>$examTypeSelectField, "levelSelectField"=>$levelSelectField]);
    }

    public function orderBy($column) {
        if(session()->get('ExamDescriptionOrderBy[0]') == $column)
            if(session()->get('ExamDescriptionOrderBy[1]') == 'desc') session()->put('ExamDescriptionOrderBy[1]', 'asc');
            else session()->put('ExamDescriptionOrderBy[1]', 'desc');
        else {
            session()->put('ExamDescriptionOrderBy[4]', session()->get('ExamDescriptionOrderBy[2]'));
            session()->put('ExamDescriptionOrderBy[2]', session()->get('ExamDescriptionOrderBy[0]'));
            session()->put('ExamDescriptionOrderBy[0]', $column);
            session()->put('ExamDescriptionOrderBy[5]', session()->get('ExamDescriptionOrderBy[3]'));
            session()->put('ExamDescriptionOrderBy[3]', session()->get('ExamDescriptionOrderBy[1]'));
            session()->put('ExamDescriptionOrderBy[1]', 'asc');
        }
        return redirect( route('opis_egzaminu.index') );
    }

    public function create(Request $request, SubjectRepository $subjectRepo, SessionRepository $sessionRepo) {
        $subjects = $subjectRepo -> getActualAndSorted();
        $subjectSelected = session()->get('sessionSelected');
        $subjectSelectField = view('subject.selectField', ["subjects"=>$subjects, "subjectSelected"=>$subjectSelected]);
        $examTypes = array('pisemny', 'ustny');
        $examTypeSelected = 'pisemny';
        $examTypeSelectField = view('examDescription.examTypeSelectField', ["examTypes"=>$examTypes, "examTypeSelected"=>$examTypeSelected]);
        $levels = array('podstawowy', 'rozszerzony', 'nieokreślony', 'dwujęzyczny');
        $levelSelected = 'dwujęzyczny';
        $levelSelectField = view('layouts.levelSelectField', ["levels"=>$levels, "levelSelected"=>$levelSelected]);

        if($request->version=="forSession")
            return view('examDescription.createForSession', ["session_id"=>$request->session_id, "subjectSelectField"=>$subjectSelectField, "examTypeSelectField"=>$examTypeSelectField, "levelSelectField"=>$levelSelectField]);

        $sessions = $sessionRepo -> getAllSorted();
        $sessionSelected = session()->get('sessionSelected');
        $sessionSelectField = view('session.selectField', ["sessions"=>$sessions, "sessionSelected"=>$sessionSelected]);
        return view('examDescription.create', ["sessionSelectField"=>$sessionSelectField, "subjectSelectField"=>$subjectSelectField, "examTypeSelectField"=>$examTypeSelectField, "levelSelectField"=>$levelSelectField]);
    }

    public function store(Request $request) {
        $this -> validate($request, [
          'session_id' => 'required',
          'subject_id' => 'required',
          'exam_type' => 'required',
          'level' => 'required',
          'max_points' => 'integer|max:100',
        ]);

        $examDescription = new ExamDescription;
        $examDescription->session_id = $request->session_id;
        $examDescription->subject_id = $request->subject_id;
        $examDescription->type = $request->exam_type;
        $examDescription->level = $request->level;
        if($request->max_points!='')  $examDescription->max_points = $request->max_points;
        $examDescription -> save();
        return $examDescription->id;
    }

    public function change($id) {  session()->put('examDescriptionSelected', $id); }

    public function show($id, ExamDescriptionRepository $examDescriptionRepo, ClassroomRepository $classroomRepo, DeclarationRepository $declarationRepo, ExamRepository $examRepo, TermRepository $termRepo, SessionRepository $sessionRepo, $view='') {
        if(empty(session()->get('examDescriptionView')))  session()->put('examDescriptionView', 'showInfo');
        if($view)  session()->put('examDescriptionView', $view);
        $examDescription = $examDescriptionRepo -> find($id);
        session()->put('examDescriptionSelected', $id);

        $examDescriptions = $examDescriptionRepo -> getFilteredAndSorted(0, 0, 0, 0);
        list($this->previous, $this->next) = $examDescriptionRepo -> nextAndPreviousRecordId($examDescriptions, $id);

        switch(session()->get('examDescriptionView')) {
            case 'showInfo':    return $this -> showInfo($examDescription);
            case 'showTerms':   return $this -> showTerms($examDescription, $classroomRepo, $termRepo, $sessionRepo);
            case 'showExams':   return $this -> showExams($examDescription, $declarationRepo, $termRepo, $examRepo);
            default:
                printf('<p style="background: #bb0; color: #f00; font-size: x-large; text-align: center; border: 3px solid red; padding: 5px;">Widok %s nieznany</p>', $view);
        }
    }

    private function showInfo($examDescription) {
        $examDescriptionInfo = view('examDescription.showInfo', ["examDescription"=>$examDescription]);
        return view('examDescription.show', ["examDescription"=>$examDescription, "previous"=>$this->previous, "next"=>$this->next, "css"=>'', "js"=>'', "subView"=>$examDescriptionInfo]);
    }

    private function showTerms($examDescription, $classroomRepo, $termRepo, $sessionRepo) {
        $classroomSelected = session()->get('classroomSelected');
        $classrooms = $classroomRepo -> getAllSorted();
        $classroomSelectField = view('classroom.selectField', ["classrooms"=>$classrooms, "classroomSelected"=>$classroomSelected]);
        $terms = $termRepo -> getFilteredAndSorted($examDescription->id, $classroomSelected, 0);
        $sessions = $sessionRepo -> getAllSorted();
        $sessionSelected = session()->get('sessionSelected');
        $sessionSelectField = view('session.selectField', ["sessions"=>$sessions, "sessionSelected"=>$sessionSelected]);
        $termTable = view('term.table', ["terms"=>$terms, "sessionSelectField"=>$sessionSelectField, "examDescriptionSelectField"=>"", "classroomSelectField"=>$classroomSelectField]);
        return view('examDescription.show', ["examDescription"=>$examDescription, "previous"=>$this->previous, "next"=>$this->next, "css"=>"", "js"=>"", "subView"=>$termTable]);
    }

    private function showExams($examDescription, $declarationRepo, $termRepo, $examRepo) {
        $classroom_id = session()->get('classroomSelected');
        $declaration_id = session()->get('declarationSelected');
        $exam_description_id = session()->get('examDescriptionSelected');
        $exam_type = session()->get('typeSelected');
        $session_id = session()->get('sessionSelected');
        $student_id = session()->get('studentSelected');
        $term_id = session()->get('termSelected');

        $declarations = $declarationRepo -> getFilteredAndSorted($session_id, 0, $student_id);
        $declarationSelectField = view('declaration.selectField', ["declarations"=>$declarations, "declarationSelected"=>$declaration_id]);

        $terms = $termRepo -> getFilteredAndSorted($exam_description_id, $classroom_id, $session_id);
        $termSelectField = view('term.selectField', ["terms"=>$terms, "termSelected"=>$term_id]);

        $examTypes = array('obowiązkowy', 'dodatkowy');
        $examTypeSelected = 'obowiązkowy';
        $examTypeSelectField = view('examDescription.examTypeSelectField', ["examTypes"=>$examTypes, "examTypeSelected"=>$examTypeSelected]);

        $exams = $examRepo -> getFilteredAndSorted($declaration_id, $examDescription->id, $term_id, 0, $exam_type);
        $countExams = $examRepo -> countExamDescriptionExams($examDescription->id);

        $css = "";
        $js = "exam/operations.js";

        $examTable = view('exam.table', ["exams"=>$exams, "countExams"=>$countExams, "declarationSelectField"=>$declarationSelectField, "termSelectField"=>$termSelectField, "examTypeSelectField"=>$examTypeSelectField, "version"=>"forExamDescription"]);

        return view('examDescription.show', ["examDescription"=>$examDescription, "previous"=>$this->previous, "next"=>$this->next, "css"=>$css, "js"=>$js, "subView"=>$examTable]);
    }

    public function edit(Request $request, ExamDescription $examDescription, SessionRepository $sessionRepo, SubjectRepository $subjectRepo) {
        $examDescription = $examDescription -> find($request->id);
        $subjects = $subjectRepo -> getActualAndSorted();
        $subjectSelectField = view('subject.selectField', ["subjects"=>$subjects, "subjectSelected"=>$examDescription->subject_id]);
        $examTypes = array('pisemny', 'ustny');
        $examTypeSelectField = view('examDescription.examTypeSelectField', ["examTypes"=>$examTypes, "examTypeSelected"=>$examDescription->type]);
        $levels = array('podstawowy', 'rozszerzony', 'nieokreślony');
        $levelSelectField = view('layouts.levelSelectField', ["levels"=>$levels, "levelSelected"=>$examDescription->level]);
        if($request->version=="forSession")
            return view('examDescription.editForSession', ["examDescription"=>$examDescription, "subjectSelectField"=>$subjectSelectField, "examTypeSelectField"=>$examTypeSelectField, "levelSelectField"=>$levelSelectField]);

        $sessions = $sessionRepo -> getAllSorted();
        $sessionSelectField = view('session.selectField', ["sessions"=>$sessions, "sessionSelected"=>$examDescription->session_id]);
        return view('examDescription.edit', ["examDescription"=>$examDescription, "sessionSelectField"=>$sessionSelectField, "subjectSelectField"=>$subjectSelectField, "examTypeSelectField"=>$examTypeSelectField, "levelSelectField"=>$levelSelectField]);
    }

    public function update(Request $request, ExamDescription $examDescription) {
        $examDescription = $examDescription -> find($request->id);
        $this -> validate($request, [
          'session_id' => 'required',
          'subject_id' => 'required',
          'exam_type' => 'required',
          'level' => 'required',
          'max_points' => 'integer|max:100',
        ]);

        $examDescription->session_id = $request->session_id;
        $examDescription->subject_id = $request->subject_id;
        $examDescription->type = $request->exam_type;
        $examDescription->level = $request->level;
        if($request->max_points!='')  $examDescription->max_points = $request->max_points;
        else $examDescription->max_points = NULL;
        $examDescription -> save();

        return $examDescription->id;
    }

    public function destroy($id, ExamDescription $examDescription) {
        $examDescription = $examDescription -> find($id);
        $examDescription -> delete();
        return 1;
    }

    public function refreshRow(Request $request, ExamDescription $examDescription) {
        $examDescription = $examDescription -> find($request->id);
        if($request->version=="forSession")     return view('examDescription.rowForSession', ["examDescription"=>$examDescription, "lp"=>$request->lp]);
        return view('examDescription.row', ["examDescription"=>$examDescription, "lp"=>$request->lp]);
    }
}