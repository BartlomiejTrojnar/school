<?php
namespace App\Http\Controllers;
use App\Models\Session;
use App\Repositories\SessionRepository;
//use App\Models\ExamDescription;
//use App\Models\Term;
use Illuminate\Http\Request;

class SessionController extends Controller
{
    public function index(SessionRepository $sessionRepo)
    {
        for($i=0; $i<4; $i++)
          $orderBy[$i] = session()->get("SessionOrderBy[$i]");

        $sessions = $sessionRepo->getAll($orderBy);
        return view('session.index', ["sessions"=>$sessions]);
    }

    public function orderBy($column)
    {
        if(session()->get('SessionOrderBy[0]') == $column)
          if(session()->get('SessionOrderBy[1]') == 'desc')
            session()->put('SessionOrderBy[1]', 'asc');
          else
            session()->put('SessionOrderBy[1]', 'desc');
        else
        {
          session()->put('SessionOrderBy[2]', session()->get('SessionOrderBy[0]'));
          session()->put('SessionOrderBy[0]', $column);
          session()->put('SessionOrderBy[3]', session()->get('SessionOrderBy[1]'));
          session()->put('SessionOrderBy[1]', 'asc');
        }
        return redirect( route('sesja.index') );
    }

    public function create()
    {
        $types = array('maj', 'sierpień', 'styczeń');
        return view('session.create')
             ->nest('typeSelectField', 'session.typeSelectField', ["types"=>$types, "selectedType"=>'maj']);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
          'year' => 'required|integer|min:2004',
          'type' => 'required',
        ]);

        $session = new Session;
        $session->year = $request->year;
        $session->type = $request->type;
        $session->save();

        return redirect($request->history_view);
     }

    public function show($id, $view='', SessionRepository $sessionRepo)
    {
        if(empty(session()->get('sessionView')))  session()->put('sessionView', 'showInfo');
        if($view)  session()->put('sessionView', $view);
        $session = $sessionRepo -> find($id);
        $previous = $sessionRepo -> PreviousRecordId($id);
        $next = $sessionRepo -> NextRecordId($id);

        switch(session()->get('sessionView')) {
          case 'showInfo':
              return view('session.showInfo', ["session"=>$session, "previous"=>$previous, "next"=>$next]);
              exit;
          break;
/*
          case 'showExamDescriptions':
              $examDescriptions = ExamDescription::all()->where('session_id', $id);
              return view('session.showExamDescriptions', ["session"=>$session, "examDescriptions"=>$examDescriptions, "previous"=>$previous, "next"=>$next]);
              exit;
          break;
*/
          case 'showDeclarations':
              $declarations = $session -> declarations;
              return view('session.showDeclarations', ["session"=>$session, "previous"=>$previous, "next"=>$next])
                  -> nest('declarationsTable', 'declaration.showDeclarationsTable', ["declarations"=>$declarations]);
              exit;
          break;
/*
          case 'showTerms':
              $terms = Term::all()->where('session_id', $id);
              return view('session.showTerms', ["session"=>$session, "terms"=>$terms, "previous"=>$previous, "next"=>$next]);
              exit;
          break;
*/
          default:
              printf('<p style="background: #bb0; color: #f00; font-size: x-large; text-align: center; border: 3px solid red; padding: 5px;">Widok %s nieznany</p>', $view);
              exit;
          break;
        }
    }

    public function edit(Session $sesja)
    {
        $types = array('maj', 'sierpień', 'styczeń');
        return view('session.edit', ["session"=>$sesja])
             ->nest('typeSelectField', 'session.typeSelectField', ["types"=>$types, "selectedType"=>$sesja->type]);
    }

    public function update(Request $request, Session $sesja)
    {
        $this->validate($request, [
          'year' => 'required|integer|min:2004',
          'type' => 'required',
        ]);

        $sesja->year = $request->year;
        $sesja->type = $request->type;
        $sesja->save();

        return redirect($request->history_view);
    }

    public function destroy(Session $sesja)
    {
        $sesja->delete();
        return redirect( $_SERVER['HTTP_REFERER'] );
    }
}
