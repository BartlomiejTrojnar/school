<?php
namespace App\Http\Controllers;
use App\Models\Declaration;
//use App\Repositories\DeclarationRepository;
use App\Repositories\StudentRepository;
use App\Repositories\SessionRepository;
use Illuminate\Http\Request;

class DeclarationController extends Controller
{
    public function index(DeclarationRepository $declarationRepo, SessionRepository $sessionRepo, StudentRepository $studentRepo)
    {
        for($i=0; $i<6; $i++)
          $orderBy[$i] = session()->get("DeclarationOrderBy[$i]");

        $declarations = $declarationRepo->getAll($orderBy);
        $students = $studentRepo->getAll();
        $sessions = $sessionRepo->getAll();
        return view('declaration.index', ["declarations"=>$declarations])
             ->nest('sessionSelectField', 'session.selectField', ["sessions"=>$sessions, "selectedSession"=>0])
             ->nest('studentSelectField', 'student.selectField', ["students"=>$students, "selectedStudent"=>0]);
    }

    public function orderBy($column)
    {
        if(session()->get('DeclarationOrderBy[0]') == $column)
          if(session()->get('DeclarationOrderBy[1]') == 'desc')
            session()->put('DeclarationOrderBy[1]', 'asc');
          else
            session()->put('DeclarationOrderBy[1]', 'desc');
        else
        {
          session()->put('DeclarationOrderBy[4]', session()->get('DeclarationOrderBy[2]'));
          session()->put('DeclarationOrderBy[2]', session()->get('DeclarationOrderBy[0]'));
          session()->put('DeclarationOrderBy[0]', $column);
          session()->put('DeclarationOrderBy[5]', session()->get('DeclarationOrderBy[3]'));
          session()->put('DeclarationOrderBy[3]', session()->get('DeclarationOrderBy[1]'));
          session()->put('DeclarationOrderBy[1]', 'asc');
        }
        return redirect( route('deklaracja.index') );
    }

    public function create(SessionRepository $sessionRepo, StudentRepository $studentRepo)
    {
        $students = $studentRepo->getAll();
        $sessions = $sessionRepo->getAll();
        return view('declaration.create')
             ->nest('sessionSelectField', 'session.selectField', ["sessions"=>$sessions, "selectedSession"=>0])
             ->nest('studentSelectField', 'student.selectField', ["students"=>$students, "selectedStudent"=>0]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
          'student_id' => 'required',
          'session_id' => 'required',
          'application_number' => 'required|integer|min:1|max:10',
          'student_code' => 'max:3',
        ]);

        $declaration = new Declaration;
        $declaration->student_id = $request->student_id;
        $declaration->session_id = $request->session_id;
        $declaration->application_number = $request->application_number;
        $declaration->student_code = $request->student_code;
        $declaration->save();

        return redirect($request->history_view);
    }

    public function show($id, $view='', DeclarationRepository $declarationRepo)
    {
        if(empty(session()->get('declarationView')))  session()->put('declarationView', 'showInfo');
        if($view)  session()->put('declarationView', $view);
        $declaration = $declarationRepo -> find($id);
        $previous = $declarationRepo -> PreviousRecordId($id);
        $next = $declarationRepo -> NextRecordId($id);

        switch(session()->get('declarationView')) {
             case 'showInfo':
               return view('declaration.showInfo', ["declaration"=>$declaration, "previous"=>$previous, "next"=>$next]);
               exit;
             break;
             case 'showExams':
               //$studentClasses = StudentClass::all() -> where('grade_id', $id);
               return view('declaration.showExams', ["declaration"=>$declaration, "previous"=>$previous, "next"=>$next]);
               exit;
             break;
             default:
               printf('<p style="background: #bb0; color: #f00; font-size: x-large; text-align: center; border: 3px solid red; padding: 5px;">Widok %s nieznany</p>', $view);
               exit;
             break;
        }
    }


    public function edit(Declaration $deklaracja, SessionRepository $sessionRepo, StudentRepository $studentRepo)
    {
        $students = $studentRepo->getAll();
        $sessions = $sessionRepo->getAll();
        return view('declaration.edit', ["declaration"=>$deklaracja])
             ->nest('sessionSelectField', 'session.selectField', ["sessions"=>$sessions, "selectedSession"=>$deklaracja->session_id])
             ->nest('studentSelectField', 'student.selectField', ["students"=>$students, "selectedStudent"=>$deklaracja->student_id]);
    }

    public function update(Request $request, Declaration $deklaracja)
    {
        $this->validate($request, [
          'student_id' => 'required',
          'session_id' => 'required',
          'application_number' => 'required|integer|min:1|max:10',
          'student_code' => 'max:3',
        ]);

        $deklaracja->student_id = $request->student_id;
        $deklaracja->session_id = $request->session_id;
        $deklaracja->application_number = $request->application_number;
        $deklaracja->student_code = $request->student_code;
        $deklaracja->save();

        return redirect($request->history_view);
    }

    public function destroy(Declaration $deklaracja)
    {
        $deklaracja->delete();
        return redirect( $_SERVER['HTTP_REFERER'] );
    }
}
