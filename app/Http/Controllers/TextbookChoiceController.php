<?php
// ------------------------ (C) mgr inż. Bartłomiej Trojnar; 16.10.2021 ------------------------ //
namespace App\Http\Controllers;
use App\Models\TextbookChoice;
use App\Repositories\TextbookChoiceRepository;

use App\Repositories\SchoolRepository;
use App\Repositories\SchoolYearRepository;
use App\Repositories\SubjectRepository;
use App\Repositories\TextbookRepository;
use Illuminate\Http\Request;

class TextbookChoiceController extends Controller
{
    public function index(TextbookChoiceRepository $textbookChoiceRepo, SchoolRepository $schoolRepo, SchoolYearRepository $schoolYearRepo, SubjectRepository $subjectRepo) {
        $level = session()->get('levelSelected');
        $school = session()->get('schoolSelected');
        $schoolYear = session()->get('schoolYearSelected');
        $studyYear = session()->get('studyYearSelected');
        $subject = session()->get('subjectSelected');

        $levels = array('podstawowy', 'rozszerzony', 'nieokreślony');
        $schools = $schoolRepo->getAllSorted();
        $schoolYears = $schoolYearRepo->getAllSorted();
        $studyYears = array(1, 2, 3, 4);
        $subjects = $subjectRepo -> getActualAndSorted();
        $textbookChoices = $textbookChoiceRepo -> getPaginate($school, $schoolYear, $subject, $studyYear, $level);

        $levelSelectField = view('layouts.levelSelectField', ["levels"=>$levels, "levelSelected"=>$level]);
        $schoolSelectField = view('school.selectField', ["schools"=>$schools, "schoolSelected"=>$school]);
        $schoolYearSelectField = view('schoolYear.selectField', ["name"=>"school_year_id", "schoolYears"=>$schoolYears, "schoolYearSelected"=>$schoolYear]);
        $studyYearSelectField = view('layouts.studyYearSelectField', ["studyYears"=>$studyYears, "studyYearSelected"=>$studyYear]);
        $subjectSelectField = view('subject.selectField', ["subjects"=>$subjects, "subjectSelected"=>$subject]);

        return view('textbookChoice.index', ["textbookChoices"=>$textbookChoices, "links"=>true, "schoolSelectField"=>$schoolSelectField,
            "schoolYearSelectField"=>$schoolYearSelectField, "subjectSelectField"=>$subjectSelectField,
            "studyYearSelectField"=>$studyYearSelectField, "levelSelectField"=>$levelSelectField]);
    }

    public function orderBy($column) {
        if(session()->get('TextbookChoiceOrderBy[0]') == $column)
            if(session()->get('TextbookChoiceOrderBy[1]') == 'desc')  session()->put('TextbookChoiceOrderBy[1]', 'asc');
            else  session()->put('TextbookChoiceOrderBy[1]', 'desc');
        else {
            session()->put('TextbookChoiceOrderBy[4]', session()->get('TextbookChoiceOrderBy[2]'));
            session()->put('TextbookChoiceOrderBy[2]', session()->get('TextbookChoiceOrderBy[0]'));
            session()->put('TextbookChoiceOrderBy[0]', $column);
            session()->put('TextbookChoiceOrderBy[5]', session()->get('TextbookChoiceOrderBy[3]'));
            session()->put('TextbookChoiceOrderBy[3]', session()->get('TextbookChoiceOrderBy[1]'));
            session()->put('TextbookChoiceOrderBy[1]', 'asc');
        }
        return redirect( $_SERVER['HTTP_REFERER'] );
    }

    public function create(Request $request, TextbookRepository $textbookRepo, SchoolRepository $schoolRepo, SchoolYearRepository $schoolYearRepo) {
        $schools = $schoolRepo -> getAllSorted();
        $schoolSelectField = view('school.selectField', ["schools"=>$schools, "schoolSelected"=>session()->get('schoolSelected')]);
        $schoolYears = $schoolYearRepo -> getAllSorted();
        $schoolYearSelectField = view('schoolYear.selectField', ["schoolYears"=>$schoolYears, "schoolYearSelected"=>session()->get('schoolYearSelected'), "name"=>'school_year_id']);
        $levels = array('podstawowy', 'rozszerzony', 'nieokreślony');
        $levelSelectField = view('layouts.levelSelectField', ["levels"=>$levels, "levelSelected"=>'podstawowy']);

        if($request->version == "forIndex")     return $this->createRowForIndex($schoolSelectField, $schoolYearSelectField, $levelSelectField, $textbookRepo);
        if($request->version == "forTextbook")
            return view('textbookChoice.createRowForTextbook', ["textbook_id"=>$request->textbook_id, "schoolSelectField"=>$schoolSelectField, "schoolYearSelectField"=>$schoolYearSelectField, "levelSelectField"=>$levelSelectField]);
            //return view('textbookChoice.createRowForTextbook', ["textbook_id"=>$textbook_id, "schoolSelectField"=>$schoolSelectField, "schoolYearSelectField"=>$schoolYearSelectField, "levelSelectField"=>$levelSelectField]);
    }

    private function createRowForIndex($schoolSelectField, $schoolYearSelectField, $levelSelectField, $textbookRepo) {
        $textbookSelected = session()->get('textbookSelected');
        $textbooks = $textbookRepo -> getAll( session()->get('subjectSelected') );
        $textbookSelectField = view('textbook.selectField', ["textbooks"=>$textbooks, "textbookSelected"=>$textbookSelected]);
        return view('textbookChoice.createRowForIndex', ["textbookSelectField"=>$textbookSelectField, "schoolSelectField"=>$schoolSelectField, "schoolYearSelectField"=>$schoolYearSelectField, "levelSelectField"=>$levelSelectField]);
    }

    public function store(Request $request) {
        $this->validate($request, [
          'textbook_id' => 'required',
          'school_id' => 'required',
          'school_year_id' => 'required',
          'learning_year' => 'required|integer|between:1,4',
        ]);

        $textbookChoice = new TextbookChoice;
        $textbookChoice->textbook_id = $request->textbook_id;
        $textbookChoice->school_id = $request->school_id;
        $textbookChoice->school_year_id = $request->school_year_id;
        $textbookChoice->learning_year = $request->learning_year;
        $textbookChoice->level = $request->level;
        $textbookChoice->save();

        return $textbookChoice->id;
    }

    public function extension(Request $request, TextbookChoice $textbookChoice_old) {
        $textbookChoice_old = $textbookChoice_old -> find($request->id);
        $textbookChoice = new TextbookChoice;
        $textbookChoice->textbook_id    = $textbookChoice_old->textbook_id;
        $textbookChoice->school_id      = $textbookChoice_old->school_id;
        $textbookChoice->school_year_id = $textbookChoice_old->school_year_id+1;
        $textbookChoice->learning_year  = $textbookChoice_old->learning_year;
        $textbookChoice->level          = $textbookChoice_old->level;
        $textbookChoice -> save();
        return $textbookChoice->id;
    }

    public function verifyProlong($id, TextbookChoiceRepository $textbookChoiceRepo) {
        $oldTBC = new TextbookChoice;
        $oldTBC = $textbookChoiceRepo -> find($id);
        $textbookChoice_news = $textbookChoiceRepo -> numberOfChoices($oldTBC->textbook_id, $oldTBC->school_id, $oldTBC->school_year_id+1, $oldTBC->learning_year, $oldTBC->level);
        if($textbookChoice_news>0) return $id;
        return 0;
    }

    public function edit(Request $request, $id, TextbookChoice $textbookChoice, SchoolRepository $schoolRepo, SchoolYearRepository $schoolYearRepo) {
        $textbookChoice = $textbookChoice -> find($id);
        $schools = $schoolRepo -> getAllSorted();
        $schoolSelectField = view('school.selectField', ["schools"=>$schools, "schoolSelected"=>$textbookChoice->school_id]);
        $schoolYears = $schoolYearRepo -> getAllSorted();
        $schoolYearSelectField = view('schoolYear.selectField', ["schoolYears"=>$schoolYears, "schoolYearSelected"=>$textbookChoice->school_year_id, "name"=>'school_year_id']);
        $levels = array('podstawowy', 'rozszerzony', 'nieokreślony');
        $levelSelectField = view('layouts.levelSelectField', ["levels"=>$levels, "levelSelected"=>$textbookChoice->level]);

        if($request->version == "forIndex")
            return view('textbookChoice.editRowForIndex', ["textbookChoice"=>$textbookChoice, "schoolSelectField"=>$schoolSelectField, "schoolYearSelectField"=>$schoolYearSelectField, "levelSelectField"=>$levelSelectField]);
        if($request->version == "forTextbook")
            return view('textbookChoice.editRowForTextbook', ["textbookChoice"=>$textbookChoice, "schoolSelectField"=>$schoolSelectField, "schoolYearSelectField"=>$schoolYearSelectField, "levelSelectField"=>$levelSelectField]);
    }

    public function update($id, Request $request, TextbookChoice $textbookChoice) {
        $textbookChoice = $textbookChoice -> find($id);
        $this->validate($request, [
          'textbook_id' => 'required',
          'school_id' => 'required',
          'school_year_id' => 'required',
          'learning_year' => 'required|integer|between:1,4',
        ]);

        $textbookChoice->textbook_id = $request->textbook_id;
        $textbookChoice->school_id = $request->school_id;
        $textbookChoice->school_year_id = $request->school_year_id;
        $textbookChoice->learning_year = $request->learning_year;
        $textbookChoice->level = $request->level;
        $textbookChoice->save();

        return $textbookChoice->id;
    }

    public function destroy($id, TextbookChoice $textbookChoice) {
        $textbookChoice = $textbookChoice -> find($id);
        $textbookChoice -> delete();
        return 1;
    }

    public function refreshRow(Request $request, TextbookChoiceRepository $textbookChoiceRepo) {
        $this->textbookChoice = $textbookChoiceRepo -> find($request->id);

        if($request->version == "forIndex")     return view('textbookChoice.rowForIndex', ["textbookChoice"=>$this->textbookChoice, "lp"=>$request->lp]);
        if($request->version == "forTextbook")  return view('textbookChoice.rowForTextbook', ["textbookChoice"=>$this->textbookChoice, "lp"=>$request->lp]);
        return $request->version;
    }
}