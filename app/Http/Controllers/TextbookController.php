<?php
// ------------------------ (C) mgr inż. Bartłomiej Trojnar; 13.10.2021 ------------------------ //
namespace App\Http\Controllers;
use App\Models\Textbook;
use App\Repositories\TextbookRepository;

use App\Repositories\SchoolRepository;
use App\Repositories\SchoolYearRepository;
use App\Repositories\SubjectRepository;
use App\Repositories\TeacherRepository;
use App\Repositories\TextbookChoiceRepository;
use Illuminate\Http\Request;

class TextbookController extends Controller
{
    public function index(TextbookRepository $textbookRepo, SubjectRepository $subjectRepo) {
        $subjects = $subjectRepo -> getActualAndSorted();
        $subjectSelected = session()->get('subjectSelected');
        $subjectSelectField = view('subject.selectField', ["subjects"=>$subjects, "subjectSelected"=>$subjectSelected]);

        $textbooks = $textbookRepo -> getPaginate($subjectSelected);
        $textbookTable = view('textbook.table', ["textbooks"=>$textbooks, "links"=>true, "subTitle"=>"", "subjectSelectField"=>$subjectSelectField]);

        return view('textbook.index', ["textbook"=>$textbooks[0], "textbookTable"=>$textbookTable]);
    }

    public function orderBy($column) {
        if(session()->get('TextbookOrderBy[0]') == $column)
            if(session()->get('TextbookOrderBy[1]') == 'desc')  session()->put('TextbookOrderBy[1]', 'asc');
            else  session()->put('TextbookOrderBy[1]', 'desc');
        else {
            session()->put('TextbookOrderBy[4]', session()->get('TextbookOrderBy[2]'));
            session()->put('TextbookOrderBy[2]', session()->get('TextbookOrderBy[0]'));
            session()->put('TextbookOrderBy[0]', $column);
            session()->put('TextbookOrderBy[5]', session()->get('TextbookOrderBy[3]'));
            session()->put('TextbookOrderBy[3]', session()->get('TextbookOrderBy[1]'));
            session()->put('TextbookOrderBy[1]', 'asc');
        }

        return redirect( route('podrecznik.index') );
    }

    public function create(Request $request, SubjectRepository $subjectRepo) {
        if($request->version == "forIndex")     return $this -> createRow($subjectRepo);
        return $request->version;
    }

    private function createRow($subjectRepo) {
        $subjects = $subjectRepo -> getActualAndSorted();
        $subjectSelected = session()->get('subjectSelected');
        $subjectSelectField = view('subject.selectField', ["subjects"=>$subjects, "subjectSelected"=>$subjectSelected]);
        return view('textbook.create', ["subjectSelectField"=>$subjectSelectField]);
    }

    public function store(Request $request) {
        $this->validate($request, [
          'subject_id' => 'required',
          'author' => 'max:80',
          'title' => 'required|max:125',
          'publishing_house' => 'max:30',
          'admission' => 'max:20',
          'comments' => 'max:60',
        ]);

        $textbook = new Textbook;
        $textbook->subject_id = $request->subject_id;
        $textbook->author = $request->author;
        $textbook->title = $request->title;
        $textbook->publishing_house = $request->publishing_house;
        $textbook->admission = $request->admission;
        $textbook->comments = $request->comments;
        $textbook->save();

        return $textbook->id;
    }

    public function show($id, TextbookRepository $textbookRepo, SchoolRepository $schoolRepo, SchoolYearRepository $schoolYearRepo, TextbookChoiceRepository $textbookChoiceRepo, TeacherRepository $teacherRepo, $view='') {
        session()->put('textbookSelected', $id);
        if(empty( session()->get('textbookView') ))  session()->put('textbookView', 'showInfo');
        if($view)  session()->put('textbookView', $view);
        $this->textbook = $textbookRepo -> find($id);

        $subjectSelected = session()->get('subjectSelected');
        $textbooks = $textbookRepo -> getAll($subjectSelected);
        list($this->previous, $this->next) = $textbookRepo -> nextAndPreviousRecordId($textbooks, $id);

        switch( session()->get('textbookView') ) {
            case 'showInfo':  return $this -> showInfo($schoolRepo, $schoolYearRepo, $textbookChoiceRepo, $teacherRepo);
            default:
                printf('<p style="background: #bb0; color: #f00; font-size: x-large; text-align: center; border: 3px solid red; padding: 5px;">Widok %s nieznany</p>', session()->get('teacherView'));
        }
    }

    private function showInfo($schoolRepo, $schoolYearRepo, $textbookChoiceRepo, $teacherRepo) {
        $school     = session()->get('schoolSelected');
        $schoolYear = session()->get('schoolYearSelected');
        $studyYear  = session()->get('studyYearSelected');
        $level      = session()->get('levelSelected');
        $textbookChoices= $textbookChoiceRepo -> getForTextbook($this->textbook->id, $school, $schoolYear, $studyYear, $level);

        $schools        = $schoolRepo -> getAllSorted();
        $schoolSelectField      = view('school.selectField', ["schools"=>$schools, "schoolSelected"=>$school]);
        $schoolYears    = $schoolYearRepo -> getAllSorted();
        $schoolYearSelectField  = view('schoolYear.selectField', ["name"=>"school_year_id", "schoolYears"=>$schoolYears, "schoolYearSelected"=>$schoolYear]);
        $studyYears     = array(1, 2, 3, 4);
        $studyYearSelectField   = view('layouts.studyYearSelectField', ["studyYears"=>$studyYears, "studyYearSelected"=>$studyYear]);
        $levels         = array('podstawowy', 'rozszerzony', 'nieokreślony');
        $levelSelectField       = view('layouts.levelSelectField', ["levels"=>$levels, "levelSelected"=>$level]);

        $textbookChoicesTable = view('textbookChoice.tableForTextbook', ["textbookChoices"=>$textbookChoices, "schoolSelectField"=>$schoolSelectField, 
            "schoolYearSelectField"=>$schoolYearSelectField, "studyYearSelectField"=>$studyYearSelectField, "levelSelectField"=>$levelSelectField, "teacherSelectField"=>'usunąć']);
        $textbookInfo = view('textbook.showInfo', ["textbook"=>$this->textbook, "textbookChoicesTable"=>$textbookChoicesTable]);
        $css = "";
        $js = "textbook/textbookChoices.js";

        return view('textbook.show', ["textbook"=>$this->textbook, "previous"=>$this->previous, "next"=>$this->next, "css"=>$css, "js"=>$js, "subView"=>$textbookInfo]);
    }

    public function edit(Request $request, Textbook $textbook, SubjectRepository $subjectRepo) {
        $textbook = $textbook -> find($request->id);
        $subjects = $subjectRepo->getActualAndSorted();
        $subjectSelectField = view('subject.selectField', ["subjects"=>$subjects, "subjectSelected"=>$textbook->subject_id]);
        return view('textbook.edit', ["textbook"=>$textbook, "subjectSelectField"=>$subjectSelectField, "lp"=>$request->lp]);
    }

    public function update($id, Request $request, Textbook $textbook) {
        $textbook = $textbook -> find($id);
        $this->validate($request, [
          'subject_id' => 'required',
          'author' => 'max:80',
          'title' => 'required|max:125',
          'publishing_house' => 'max:30',
          'admission' => 'max:20',
          'comments' => 'max:60',
        ]);

        $textbook->subject_id = $request->subject_id;
        $textbook->author = $request->author;
        $textbook->title = $request->title;
        $textbook->publishing_house = $request->publishing_house;
        $textbook->admission = $request->admission;
        $textbook->comments = $request->comments;
        $textbook -> save();

        return $textbook->id;
    }

    public function destroy($id, Textbook $textbook) {
        $textbook = $textbook -> find($id);
        $textbook -> delete();
        return 1;
    }

    public function refreshRow(Request $request, TextbookRepository $textbookRepo) {
        $this->textbook = $textbookRepo -> find($request->id);
        return view('textbook.row', ["textbook"=>$this->textbook, "lp"=>$request->lp]);
    }
}
