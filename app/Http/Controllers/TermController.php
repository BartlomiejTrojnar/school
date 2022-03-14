<?php
// ----------------------- (C) mgr inż. Bartłomiej Trojnar; (I) grudzień 2020 ----------------------- //
namespace App\Http\Controllers;
use App\Models\Term;
use App\Repositories\TermRepository;

use App\Models\Exam;
use App\Repositories\ClassroomRepository;
use App\Repositories\ExamRepository;
use App\Repositories\ExamDescriptionRepository;
use App\Repositories\SessionRepository;
use Illuminate\Http\Request;

class TermController extends Controller
{
    public function index(TermRepository $termRepo, ExamDescriptionRepository $examDescriptionRepo, ClassroomRepository $classroomRepo, SessionRepository $sessionRepo) {
        $examDescriptions = $examDescriptionRepo -> getAllSorted();
        $examDescriptionSelected = session()->get('examDescriptionSelected');
        $examDescriptionSelectField = view('examDescription.selectField', ["examDescriptions"=>$examDescriptions, "examDescriptionSelected"=>$examDescriptionSelected]);

        $classroomSelected = session()->get('classroomSelected');
        $classrooms = $classroomRepo -> getAllSorted();
        $classroomSelectField = view('classroom.selectField', ["classrooms"=>$classrooms, "classroomSelected"=>$classroomSelected]);

        $sessionSelected = session()->get('sessionSelected');
        $sessions = $sessionRepo -> getAllSorted();
        $sessionSelectField = view('session.selectField', ["sessions"=>$sessions, "sessionSelected"=>$sessionSelected]);
        $terms = $termRepo -> getFilteredAndSorted($sessionSelected, $examDescriptionSelected, $classroomSelected);
        $examDescriptions = $examDescriptionRepo -> getAllSorted();
        $classrooms = $classroomRepo -> getAllSorted();

        return view('term.index')
            -> nest('termTable', 'term.table', ["terms"=>$terms, "subTitle"=>"", "links"=>true, "examDescriptionSelectField"=>$examDescriptionSelectField,
            "classroomSelectField"=>$classroomSelectField, "sessionSelectField"=>$sessionSelectField]);
    }

    public function orderBy($column) {
        if(session()->get('TermOrderBy[0]') == $column)
            if(session()->get('TermOrderBy[1]') == 'desc')  session()->put('TermOrderBy[1]', 'asc');
            else  session()->put('TermOrderBy[1]', 'desc');
        else {
            session()->put('TermOrderBy[4]', session()->get('TermOrderBy[2]'));
            session()->put('TermOrderBy[2]', session()->get('TermOrderBy[0]'));
            session()->put('TermOrderBy[0]', $column);
            session()->put('TermOrderBy[5]', session()->get('TermOrderBy[3]'));
            session()->put('TermOrderBy[3]', session()->get('TermOrderBy[1]'));
            session()->put('TermOrderBy[1]', 'asc');
        }
        return redirect( $_SERVER['HTTP_REFERER'] );
    }

    public function create(ExamDescriptionRepository $examDescriptionRepo, ClassroomRepository $classroomRepo) {
        $subject_id = session()->get('subjectSelected');
        $session_id = session()->get('sessionSelected');
        $exam_type = session()->get('typeSelected');
        $level = session()->get('levelSelected');
        $examDescriptions = $examDescriptionRepo -> getFilteredAndSorted($subject_id, $session_id, $exam_type, $level);
        $classrooms = $classroomRepo->getAllSorted();

        return view('term.create')
            -> nest('examDescriptionSelectField', 'examDescription.selectField', ["examDescriptions"=>$examDescriptions, "examDescriptionSelected"=>0])
            -> nest('classroomSelectField', 'classroom.selectField', ["classrooms"=>$classrooms, "classroomSelected"=>0]);
    }

    public function store(Request $request) {
        $this -> validate($request, [
          'exam_description_id' => 'required',
          'date_start' => 'required',
          'date_end' => 'required',
        ]);

        $term = new Term;
        $term->exam_description_id = $request->exam_description_id;
        $term->classroom_id = $request->classroom_id;
        $term->date_start = $request->date_start;
        $term->date_end = $request->date_end;
        $term -> save();

        return redirect($request->history_view);
    }

    public function show($id, TermRepository $termRepo, $view='') {
        if( empty(session() -> get('termView')) )  session() -> put('termView', 'showInfo');
        if($view)  session() -> put('termView', $view);
        $this->term = $termRepo -> find($id);
        $terms = $termRepo -> getFilteredAndSorted(0,0,0);
        list($this->previous, $this->next) = $termRepo -> nextAndPreviousRecordId($terms, $id);

        switch(session()->get('termView')) {
            case 'showInfo':    return $this -> showInfo();
            case 'showExams':   return $this -> showExams();
            default:
                printf('<p style="background: #bb0; color: #f00; font-size: x-large; text-align: center; border: 3px solid red; padding: 5px;">Widok %s nieznany</p>', $view);
        }
    }

    private function showInfo() {
        return view('term.show', ["term"=>$this->term, "previous"=>$this->previous, "next"=>$this->next])
            -> nest('subView', 'term.showInfo', ["term"=>$this->term]);
    }

    private function showExams() {
        $examRepo = new ExamRepository(new Exam);
        $exams = $examRepo -> getFilteredAndSorted(0, 0, $this->term->id, 0);
        $declarationSelectField = "";
        $examDescriptionSelectField = "";
        $termSelectField = "";
        $examTypeSelectField = "";

        return view('term.show', ["term"=>$this->term, "previous"=>$this->previous, "next"=>$this->next])
            -> nest('subView', 'exam.table', ["exams"=>$exams, "declarationSelectField"=>$declarationSelectField, "examDescriptionSelectField"=>$examDescriptionSelectField,
                "termSelectField"=>$termSelectField, "examTypeSelectField"=>$examTypeSelectField]);
    }

    public function edit($id, Term $term, ExamDescriptionRepository $examDescriptionRepo, ClassroomRepository $classroomRepo) {
        $term = $term -> find($id);
        $subject_id = session()->get('subjectSelected');
        $session_id = session()->get('sessionSelected');
        $exam_type = session()->get('typeSelected');
        $level = session()->get('levelSelected');

        $examDescriptions = $examDescriptionRepo -> getFilteredAndSorted($subject_id, $session_id, $exam_type, $level);
        $classrooms = $classroomRepo->getAllSorted();

        return view('term.edit', ["term"=>$term])
            -> nest('examDescriptionSelectField', 'examDescription.selectField', ["examDescriptions"=>$examDescriptions, "examDescriptionSelected"=>$term->exam_description_id])
            -> nest('classroomSelectField', 'classroom.selectField', ["classrooms"=>$classrooms, "classroomSelected"=>$term->classroom_id]);
    }

    public function update($id, Request $request, Term $term) {
        $term = $term -> find($id);
        $this -> validate($request, [
          'exam_description_id' => 'required',
          'date_start' => 'required',
          'date_end' => 'required',
        ]);

        $term->exam_description_id = $request->exam_description_id;
        $term->classroom_id = $request->classroom_id;
        $term->date_start = $request->date_start;
        $term->date_end = $request->date_end;
        $term -> save();

        return redirect($request->history_view);
    }

    public function destroy($id, Term $term) {
        $term = $term -> find($id);
        $term -> delete();
        return redirect( $_SERVER['HTTP_REFERER'] );
    }
}
