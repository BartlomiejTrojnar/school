<?php
namespace App\Http\Controllers;
use App\Models\TaughtSubject;
use App\Repositories\TaughtSubjectRepository;
use App\Repositories\TeacherRepository;
use App\Repositories\SubjectRepository;
use Illuminate\Http\Request;

class TaughtSubjectController extends Controller
{
    public function index(TaughtSubjectRepository $taughtSubjectRepo)
    {
        for($i=0; $i<4; $i++)
          $orderBy[$i] = session()->get("TaughtSubjectOrderBy[$i]");

        $taughtSubjects = $taughtSubjectRepo->getAll($orderBy);
        return view('taughtSubject.index', ["taughtSubjects"=>$taughtSubjects]);
    }

    public function orderBy($column)
    {
        if(session()->get('TaughtSubjectOrderBy[0]') == $column)
          if(session()->get('TaughtSubjectOrderBy[1]') == 'desc')
            session()->put('TaughtSubjectOrderBy[1]', 'asc');
          else
            session()->put('TaughtSubjectOrderBy[1]', 'desc');
        else
        {
          session()->put('TaughtSubjectOrderBy[2]', session()->get('TaughtSubjectOrderBy[0]'));
          session()->put('TaughtSubjectOrderBy[0]', $column);
          session()->put('TaughtSubjectOrderBy[3]', session()->get('TaughtSubjectOrderBy[1]'));
          session()->put('TaughtSubjectOrderBy[1]', 'asc');
        }
        return redirect( route('nauczany_przedmiot.index') );
    }

    public function create(TeacherRepository $teacherRepo, SubjectRepository $subjectRepo)
    {
        $teachers = $teacherRepo->getAll();
        $subjects = $subjectRepo->getActualSubjects();
        return view('taughtSubject.create')
             ->nest('teacherSelectField', 'teacher.selectField', ["teachers"=>$teachers, "selectedTeacher"=>0])
             ->nest('subjectSelectField', 'subject.selectField', ["subjects"=>$subjects, "selectedSubject"=>0]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
          'teacher_id' => 'required',
          'subject_id' => 'required',
        ]);

        $taughtSubject = new TaughtSubject;
        $taughtSubject->teacher_id = $request->teacher_id;
        $taughtSubject->subject_id = $request->subject_id;
        $taughtSubject->save();
        $record = TaughtSubject::orderBy('id', 'desc')->first();
        if(!empty($request->history_view))
          return redirect($request->history_view);
        return $record->id;
    }

    public function show(TaughtSubject $nauczany_przedmiot, TaughtSubjectRepository $taughtSubjectRepo)
    {
        $previous = $taughtSubjectRepo->previousRecordId($nauczany_przedmiot->id);
        $next = $taughtSubjectRepo->nextRecordId($nauczany_przedmiot->id);
        return view('taughtSubject.show', ["taughtSubject"=>$nauczany_przedmiot, "previous"=>$previous, "next"=>$next]);
    }

    public function edit(TaughtSubject $nauczany_przedmiot, TeacherRepository $teacherRepo, SubjectRepository $subjectRepo)
    {
        $teachers = $teacherRepo->getAll();
        $subjects = $subjectRepo->getAll();
        return view('taughtSubject.edit', ["taughtSubject"=>$nauczany_przedmiot])
             ->nest('teacherSelectField', 'teacher.selectField', ["teachers"=>$teachers, "selectedTeacher"=>$nauczany_przedmiot->teacher_id])
             ->nest('subjectSelectField', 'subject.selectField', ["subjects"=>$subjects, "selectedSubject"=>$nauczany_przedmiot->subject_id]);
    }

    public function update(Request $request, TaughtSubject $nauczany_przedmiot)
    {
        $this->validate($request, [
          'teacher_id' => 'required',
          'subject_id' => 'required',
        ]);

        $nauczany_przedmiot->teacher_id = $request->teacher_id;
        $nauczany_przedmiot->subject_id = $request->subject_id;
        $nauczany_przedmiot->save();

        return redirect($request->history_view);
    }

    public function delete($id, TaughtSubjectRepository $taughtSubjectRepo)
    {
        $taughtSubjectRepo -> delete($id);
    }

    public function destroy(TaughtSubject $nauczany_przedmiot)
    {
        $nauczany_przedmiot->delete();
        return redirect( $_SERVER['HTTP_REFERER'] );
    }
}
