<?php
namespace App\Http\Controllers;
//use App\Models\TextbookChoice;
//use App\Repositories\TextbookChoiceRepository;
//use App\Repositories\TextbookRepository;
//use App\Repositories\SchoolRepository;
//use App\Repositories\SchoolYearRepository;
use Illuminate\Http\Request;

class TextbookChoiceController extends Controller
{
/*
    public function index(TextbookChoiceRepository $textbookChoiceRepo)
    {
        $textbookChoices = $textbookChoiceRepo -> getAllSorted();
        return view('textbookChoice.index')
            -> nest('textbookChoiceTable', 'textbookChoice.table', ["textbookChoices"=>$textbookChoices, "subTitle"=>""]);
    }

    public function orderBy($column)
    {
        if(session()->get('TextbookChoiceOrderBy[0]') == $column)
          if(session()->get('TextbookChoiceOrderBy[1]') == 'desc')
            session()->put('TextbookChoiceOrderBy[1]', 'asc');
          else
            session()->put('TextbookChoiceOrderBy[1]', 'desc');
        else
        {
          session()->put('TextbookChoiceOrderBy[4]', session()->get('TextbookChoiceOrderBy[2]'));
          session()->put('TextbookChoiceOrderBy[2]', session()->get('TextbookChoiceOrderBy[0]'));
          session()->put('TextbookChoiceOrderBy[0]', $column);
          session()->put('TextbookChoiceOrderBy[5]', session()->get('TextbookChoiceOrderBy[3]'));
          session()->put('TextbookChoiceOrderBy[3]', session()->get('TextbookChoiceOrderBy[1]'));
          session()->put('TextbookChoiceOrderBy[1]', 'asc');
        }
        return redirect( route('wybor_podrecznika.index') );
    }

    public function create(TextbookRepository $textbookRepo, SchoolRepository $schoolRepo, SchoolYearRepository $schoolYearRepo)
    {
        $textbooks = $textbookRepo->getAll();
        $schools = $schoolRepo->getAll();
        $schoolYears = $schoolYearRepo->getAll();
        $levels = array('podstawowy', 'rozszerzony', 'nieokreślony');
        $selectedLevel = 'podstawowy';
        return view('textbookChoice.create')
             ->nest('textbookSelectField', 'textbook.selectField', ["textbooks"=>$textbooks, "selectedTextbook"=>0])
             ->nest('schoolSelectField', 'school.selectField', ["schools"=>$schools, "selectedSchool"=>0])
             ->nest('schoolYearSelectField', 'schoolYear.selectField', ["schoolYears"=>$schoolYears, "selectedSchoolYear"=>0, "name"=>'school_year_id'])
             ->nest('levelSelectField', 'layouts.levelSelectField', ["levels"=>$levels, "selectedLevel"=>$selectedLevel]);
    }

    public function store(Request $request)
    {
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

        return redirect($request->history_view);
    }

    public function show(TextbookChoice $wybor_podrecznika, TextbookChoiceRepository $textbookChoiceRepo)
    {
        $previous = $textbookChoiceRepo->previousRecordId($wybor_podrecznika->id);
        $next = $textbookChoiceRepo->nextRecordId($wybor_podrecznika->id);
        return view('textbookChoice.show', ["textbookChoice"=>$wybor_podrecznika, "previous"=>$previous, "next"=>$next]);
    }

    public function edit(TextbookChoice $wybor_podrecznika, TextbookRepository $textbookRepo, SchoolRepository $schoolRepo, SchoolYearRepository $schoolYearRepo)
    {
        $textbooks = $textbookRepo->getAll();
        $schools = $schoolRepo->getAll();
        $schoolYears = $schoolYearRepo->getAll();
        $levels = array('podstawowy', 'rozszerzony', 'nieokreślony');
        return view('textbookChoice.edit', ["textbookChoice"=>$wybor_podrecznika])
             ->nest('textbookSelectField', 'textbook.selectField', ["textbooks"=>$textbooks, "selectedTextbook"=>$wybor_podrecznika->textbook_id])
             ->nest('schoolSelectField', 'school.selectField', ["schools"=>$schools, "selectedSchool"=>$wybor_podrecznika->school_id])
             ->nest('schoolYearSelectField', 'schoolYear.selectField', ["schoolYears"=>$schoolYears, "selectedSchoolYear"=>$wybor_podrecznika->school_year_id, "name"=>'school_year_id'])
             ->nest('levelSelectField', 'layouts.levelSelectField', ["levels"=>$levels, "selectedLevel"=>$wybor_podrecznika->level]);
    }

    public function update(Request $request, TextbookChoice $wybor_podrecznika)
    {
         $this->validate($request, [
          'textbook_id' => 'required',
          'school_id' => 'required',
          'school_year_id' => 'required',
          'learning_year' => 'required|integer|between:1,4',
        ]);

        $wybor_podrecznika->textbook_id = $request->textbook_id;
        $wybor_podrecznika->school_id = $request->school_id;
        $wybor_podrecznika->school_year_id = $request->school_year_id;
        $wybor_podrecznika->learning_year = $request->learning_year;
        $wybor_podrecznika->level = $request->level;
        $wybor_podrecznika->save();

        return redirect($request->history_view);
    }

    public function destroy(TextbookChoice $wybor_podrecznika)
    {
        $wybor_podrecznika->delete();
        return redirect( $_SERVER['HTTP_REFERER'] );
    }
*/
}
