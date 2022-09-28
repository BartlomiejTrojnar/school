<?php
// ------------------------ (C) mgr inż. Bartłomiej Trojnar; 28.09.2022 ------------------------ //
namespace App\Http\Controllers;
use App\Models\Session;
use App\Repositories\SessionRepository;

use App\Models\Classroom;
use App\Models\ExamDescription;
use App\Models\Term;
use App\Repositories\ClassroomRepository;
use App\Repositories\DeclarationRepository;
use App\Repositories\GradeRepository;
use App\Repositories\ExamDescriptionRepository;
use App\Repositories\SubjectRepository;
use App\Repositories\TermRepository;
use Illuminate\Http\Request;

class SessionController extends Controller
{
    public function index(SessionRepository $sessionRepo) {
        $sessions = $sessionRepo -> getAllSortedAndPaginate();
        return view('session.index', ["sessions"=>$sessions]);
    }

    public function orderBy($column) {
        if(session()->get('SessionOrderBy[0]') == $column)
            if(session()->get('SessionOrderBy[1]') == 'desc')  session()->put('SessionOrderBy[1]', 'asc');
            else  session()->put('SessionOrderBy[1]', 'desc');
        else {
            session()->put('SessionOrderBy[2]', session()->get('SessionOrderBy[0]'));
            session()->put('SessionOrderBy[0]', $column);
            session()->put('SessionOrderBy[3]', session()->get('SessionOrderBy[1]'));
            session()->put('SessionOrderBy[1]', 'asc');
        }
        return redirect( $_SERVER['HTTP_REFERER'] );
    }

    public function create() {
        $types = array('maj', 'sierpień', 'styczeń');
        $sessionTypeSF = view('session.typeSelectField', ["types"=>$types, "typeSelected"=>'maj']);
        return view('session.create', ["typeSF"=>$sessionTypeSF]);
    }

    public function store(Request $request) {
        $this->validate($request, [
          'year' => 'required|integer|min:2004',
          'type' => 'required',
        ]);

        $session = new Session;
        $session->year = $request->year;
        $session->type = $request->type;
        $session->save();
        return $session->id;
    }

    public function change($id) {  session()->put('sessionSelected', $id);   }

    public function show($id, SessionRepository $sessionRepo, DeclarationRepository $decRe, ExamDescriptionRepository $examDRe, GradeRepository $grRe, SubjectRepository $suRe, $view='') {
        session() -> put('sessionSelected', $id);
        if( empty(session() -> get('sessionView')) )  session() -> put('sessionView', 'showInfo');
        if($view)  session() -> put('sessionView', $view);
        $session = $sessionRepo -> find($id);
        $sessions = $sessionRepo -> getAllSorted();
        list($this->previous, $this->next) = $sessionRepo -> nextAndPreviousRecordId($sessions, $id);

        switch( session() -> get('sessionView') ) {
            case 'showInfo':                return $this -> showInfo($session);
            case 'showExamDescriptions':    return $this -> showExamDescriptions($session, $suRe, $examDRe);
            case 'showDeclarations':        return $this -> showDeclarations($session, $grRe, $decRe);
            case 'showTerms':               return $this -> showTerms($session);
            default:
                printf('<p style="background: #bb0; color: #f00; font-size: x-large; text-align: center; border: 3px solid red; padding: 5px;">Widok %s nieznany</p>', $view);
        }
    }

    private function showInfo($session) {
        $subView = view('session.showInfo', ["session"=>$session]);
        return view('session.show', ["session"=>$session, "previous"=>$this->previous, "next"=>$this->next, "css"=>"", "js"=>"", "subView"=>$subView]);
    }

    private function showExamDescriptions($session, $subjectRepo, $examDescriptionRepo) {
        $subjects = $subjectRepo -> getActualAndSorted();
        $subjectSelected = session()->get('subjectSelected');
        $subjectSF = view('subject.selectField', ["subjects"=>$subjects, "subjectSelected"=>$subjectSelected]);

        $examTypes = array('pisemny', 'ustny');
        $examTypeSelected = session()->get('typeSelected');
        $examTypeSF = view('examDescription.examTypeSelectField', ["examTypes"=>$examTypes, "examTypeSelected"=>$examTypeSelected]);

        $levels = array('rozszerzony', 'podstawowy', 'nieustalony', 'dwujęzyczny');
        $levelSelected = session()->get('levelSelected');
        $levelSF = view('layouts.levelSelectField', ["levels"=>$levels, "levelSelected"=>$levelSelected]);

        $examDescriptions = $examDescriptionRepo -> getFilteredAndSorted($subjectSelected, $session->id, $examTypeSelected, $levelSelected);
        $countDesc = count($examDescriptions);
        $examDescriptionsTable = view('examDescription.tableForSession', ["examDescriptions"=>$examDescriptions, "countDesc"=>$countDesc,
            "subjectSF"=>$subjectSF, "examTypeSF"=>$examTypeSF, "levelSF"=>$levelSF]);

        $js = "examDescription/session.js";
        return view('session.show', ["session"=>$session, "previous"=>$this->previous, "next"=>$this->next, "css"=>"", "js"=>$js, "subView"=>$examDescriptionsTable]);            
    }

    private function showDeclarations($session, $gradeRepo, $declarationRepo) {
        $gradeSelected = session()->get('gradeSelected');
        $grades = $gradeRepo -> getAllSorted();
        $gradeSF = view('grade.selectField', ["grades"=>$grades, "gradeSelected"=>$gradeSelected, "name"=>"grade_id"]);
        $declarations = $declarationRepo -> getFilteredAndSortedAndPaginate($session->id, $gradeSelected, 0);
        $declarationTable = view('declaration.tableForSession', ["declarations"=>$declarations, "gradeSF"=>$gradeSF]);
        $css = "";
        $js = "declaration/operations.js";
        return view('session.show', ["session"=>$session, "previous"=>$this->previous, "next"=>$this->next, "subView"=>$declarationTable, "css"=>$css, "js"=>$js]);
    }

    private function showTerms($session) {
        $examDescriptionRepo = new ExamDescriptionRepository(new ExamDescription);
        $examDescriptions = $examDescriptionRepo -> getAllSorted();
        $examDescriptionSelected = session()->get('examDescriptionSelected');
        $examDescriptionSF = view('examDescription.selectField', ["examDescriptions"=>$examDescriptions, "examDescriptionSelected"=>$examDescriptionSelected]);

        $classroomRepo = new ClassroomRepository(new Classroom);
        $classrooms = $classroomRepo -> getAllSorted();
        $classroomSelected = session()->get('classroomSelected');
        $classroomSF = view('classroom.selectField', ["classrooms"=>$classrooms, "classroomSelected"=>$classroomSelected]);

        $termRepo = new TermRepository(new Term);
        $terms = $termRepo -> getFilteredAndSorted($examDescriptionSelected, $classroomSelected, $session->id);
        $termsTable = view('term.table', ["terms"=>$terms, "subTitle"=>"terminy w sesji", "examDescriptionSF"=>$examDescriptionSF,
            "classroomSF"=>$classroomSF, "sessionSF"=>""]);
        $css = "";
        $js = "";

        return view('session.show', ["session"=>$session, "previous"=>$this->previous, "next"=>$this->next, "css"=>$css, "js"=>$js, "subView"=>$termsTable]);
    }

    public function edit($id, Session $session) {
        $session = $session -> find($id);
        $types = array('maj', 'sierpień', 'styczeń');
        $sessionTypeSF = view('session.typeSelectField', ["types"=>$types, "typeSelected"=>$session->type]);
        return view('session.edit', ["session"=>$session, "typeSF"=>$sessionTypeSF]);
    }

    public function update(Request $request, Session $session) {
        $session = $session -> find($request->id);
        $this->validate($request, [
          'year' => 'required|integer|min:2004',
          'type' => 'required',
        ]);
        $session->year = $request->year;
        $session->type = $request->type;
        $session->save();
        return $session->id;
    }

    public function destroy($id, Session $session) {
        $session = $session -> find($id);
        $session -> delete();
    }

    public function refreshRow(Request $request, SessionRepository $sessionRepo) {
        $session = $sessionRepo -> find($request->id);
        return view('session.row', ["session"=>$session]);
    }
}