<?php
namespace App\Http\Controllers;
use App\Models\Textbook;
use App\Repositories\TextbookRepository;
use App\Repositories\SubjectRepository;
use Illuminate\Http\Request;

class TextbookController extends Controller
{
    public function index(TextbookRepository $textbookRepo)
    {
        for($i=0; $i<6; $i++)
          $orderBy[$i] = session()->get("TextbookOrderBy[$i]");

        $textbooks = $textbookRepo->getAll($orderBy);
        return view('textbook.index', ["textbooks"=>$textbooks]);
    }

    public function orderBy($column)
    {
        if(session()->get('TextbookOrderBy[0]') == $column)
          if(session()->get('TextbookOrderBy[1]') == 'desc')
            session()->put('TextbookOrderBy[1]', 'asc');
          else
            session()->put('TextbookOrderBy[1]', 'desc');
        else
        {
          session()->put('TextbookOrderBy[4]', session()->get('TextbookOrderBy[2]'));
          session()->put('TextbookOrderBy[2]', session()->get('TextbookOrderBy[0]'));
          session()->put('TextbookOrderBy[0]', $column);
          session()->put('TextbookOrderBy[5]', session()->get('TextbookOrderBy[3]'));
          session()->put('TextbookOrderBy[3]', session()->get('TextbookOrderBy[1]'));
          session()->put('TextbookOrderBy[1]', 'asc');
        }

        return redirect( route('podrecznik.index') );
    }

    public function create(SubjectRepository $subjectRepo)
    {
        $subjects = $subjectRepo->getAll();
        return view('textbook.create')
             ->nest('subjectSelectField', 'subject.selectField', ["subjects"=>$subjects, "selectedSubject"=>0]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
          'subject_id' => 'required',
          'author' => 'max:75',
          'title' => 'required|max:125',
          'publishing_house' => 'max:30',
          'admission' => 'max:18',
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

        return redirect($request->history_view);
    }

    public function show(Textbook $podrecznik, TextbookRepository $textbookRepo)
    {
        $previous = $textbookRepo->previousRecordId($podrecznik->id);
        $next = $textbookRepo->nextRecordId($podrecznik->id);
        return view('textbook.show', ["textbook"=>$podrecznik, "previous"=>$previous, "next"=>$next]);
    }

    public function edit(Textbook $podrecznik, SubjectRepository $subjectRepo)
    {
        $subjects = $subjectRepo->getAll();
        return view('textbook.edit', ["textbook"=>$podrecznik])
             ->nest('subjectSelectField', 'subject.selectField', ["subjects"=>$subjects, "selectedSubject"=>$podrecznik->subject_id]);
    }

    public function update(Request $request, Textbook $podrecznik)
    {
        $this->validate($request, [
          'subject_id' => 'required',
          'author' => 'max:75',
          'title' => 'required|max:125',
          'publishing_house' => 'max:30',
          'admission' => 'max:18',
          'comments' => 'max:60',
        ]);

        $podrecznik->subject_id = $request->subject_id;
        $podrecznik->author = $request->author;
        $podrecznik->title = $request->title;
        $podrecznik->publishing_house = $request->publishing_house;
        $podrecznik->admission = $request->admission;
        $podrecznik->comments = $request->comments;
        $podrecznik->save();

        return redirect($request->history_view);
    }

    public function destroy(Textbook $podrecznik)
    {
        $podrecznik->delete();
        return redirect( $_SERVER['HTTP_REFERER'] );
    }
}
