<?php
namespace App\Http\Controllers;
use App\Models\Subject;
use App\Repositories\SubjectRepository;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index(SubjectRepository $subjectRepo)
    {
        for($i=0; $i<6; $i++)
          $orderBy[$i] = session()->get("SubjectOrderBy[$i]");

        $subjects = $subjectRepo->getPaginate($orderBy);
        return view('subject.index', ["subjects"=>$subjects]);
    }

    public function orderBy($column)
    {
        if(session()->get('SubjectOrderBy[0]') == $column)
          if(session()->get('SubjectOrderBy[1]') == 'desc')
            session()->put('SubjectOrderBy[1]', 'asc');
          else
            session()->put('SubjectOrderBy[1]', 'desc');
        else
        {
          session()->put('SubjectOrderBy[4]', session()->get('SubjectOrderBy[2]'));
          session()->put('SubjectOrderBy[2]', session()->get('SubjectOrderBy[0]'));
          session()->put('SubjectOrderBy[0]', $column);
          session()->put('SubjectOrderBy[5]', session()->get('SubjectOrderBy[3]'));
          session()->put('SubjectOrderBy[3]', session()->get('SubjectOrderBy[1]'));
          session()->put('SubjectOrderBy[1]', 'asc');
        }
        return redirect( route('przedmiot.index') );
    }

    public function create()
    {
        return view('subject.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
          'name' => 'required|max:60',
          'short_name' => 'max:15',
          'order_in_the_sheet' => 'nullable|integer|between:1,25',
        ]);

        $subject = new Subject;
        $subject->name = $request->name;
        $subject->short_name = $request->short_name;
        if($request->actual=="on") $subject->actual = true; else $subject->actual = false;
        $subject->order_in_the_sheet = $request->order_in_the_sheet;
        if($request->expanded=="on") $subject->expanded = true; else $subject->expanded = false;
        $subject->save();

        return redirect($request->history_view);
    }

    public function show($id, $view='', SubjectRepository $subjectRepo)
    {
        if(empty(session()->get('subjectView')))  session()->put('subjectView', 'showInfo');
        if($view)  session()->put('subjectView', $view);
        $subject = $subjectRepo -> find($id);
        $previous = $subjectRepo->previousRecordId($id);
        $next = $subjectRepo->nextRecordId($id);

        switch(session()->get('subjectView')) {
            case 'showInfo':
               return view('subject.showInfo', ["subject"=>$subject, "previous"=>$previous, "next"=>$next]);
               exit;
            break;
            case 'showGroups':
               return view('subject.showGroups', ["subject"=>$subject, "previous"=>$previous, "next"=>$next]);
               exit;
            break;
            case 'showTeachers':
               return view('subject.showTeachers', ["subject"=>$subject, "previous"=>$previous, "next"=>$next]);
               exit;
            break;
            case 'showTextbooks':
               return view('subject.showTextbooks', ["subject"=>$subject, "previous"=>$previous, "next"=>$next]);
               exit;
            break;
            default:
               printf('<p style="background: #bb0; color: #f00; font-size: x-large; text-align: center; border: 3px solid red; padding: 5px;">Widok %s nieznany</p>', $view);
               exit;
            break;
        }
    }



    public function edit(Subject $przedmiot)
    {
        return view('subject.edit', ["subject"=>$przedmiot]);
    }

    public function update(Request $request, Subject $przedmiot)
    {
        $this->validate($request, [
          'name' => 'required|max:60',
          'short_name' => 'max:15',
          'order_in_the_sheet' => 'integer|between:1,25',
        ]);

        $przedmiot->name = $request->name;
        $przedmiot->short_name = $request->short_name;
        if($request->actual=="on") $przedmiot->actual = true; else $przedmiot->actual = false;
        $przedmiot->order_in_the_sheet = $request->order_in_the_sheet;
        if($request->expanded=="on") $przedmiot->expanded = true; else $przedmiot->expanded = false;
        $przedmiot->save();

        return redirect($request->history_view);
    }

    public function destroy(Subject $przedmiot)
    {
        $przedmiot->delete();
        return redirect( $_SERVER['HTTP_REFERER'] );
    }
}
