<?php
namespace App\Http\Controllers;
//use App\Models\Session;
//use App\Repositories\SessionRepository;
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

    public function show(Session $sesja, SessionRepository $sessionRepo)
    {
        $previous = $sessionRepo->previousRecordId($sesja->id);
        $next = $sessionRepo->nextRecordId($sesja->id);
        return view('session.show', ["session"=>$sesja, "previous"=>$previous, "next"=>$next]);
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
