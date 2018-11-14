<?php
namespace App\Http\Controllers;
//use App\Models\GroupStudent;
//use App\Repositories\GroupStudentRepository;
//use App\Repositories\GroupRepository;
//use App\Repositories\StudentRepository;
use Illuminate\Http\Request;

class GroupStudentController extends Controller
{
    public function index(GroupStudentRepository $groupStudentRepo)
    {
        for($i=0; $i<6; $i++)
          $orderBy[$i] = session()->get("GroupStudentOrderBy[$i]");

        $groupStudents = $groupStudentRepo->getAll($orderBy);
        return view('groupStudent.index', ["groupStudents"=>$groupStudents]);
    }

    public function orderBy($column)
    {
        if(session()->get('GroupStudentOrderBy[0]') == $column)
          if(session()->get('GroupStudentOrderBy[1]') == 'desc')
            session()->put('GroupStudentOrderBy[1]', 'asc');
          else
            session()->put('GroupStudentOrderBy[1]', 'desc');
        else
        {
          session()->put('GroupStudentOrderBy[4]', session()->get('GroupStudentOrderBy[2]'));
          session()->put('GroupStudentOrderBy[2]', session()->get('GroupStudentOrderBy[0]'));
          session()->put('GroupStudentOrderBy[0]', $column);
          session()->put('GroupStudentOrderBy[5]', session()->get('GroupStudentOrderBy[3]'));
          session()->put('GroupStudentOrderBy[3]', session()->get('GroupStudentOrderBy[1]'));
          session()->put('GroupStudentOrderBy[1]', 'asc');
        }
        return redirect( route('grupa_uczniowie.index') );
    }

    public function create(GroupRepository $groupRepo, StudentRepository $studentRepo)
    {
        $groups = $groupRepo->getAll();
        $students = $studentRepo->getAll();
        $ratings = array('1', '2', '3', '4', '5', '6', 'nieklasyfikowany/a','zwolniony/a');
        return view('groupStudent.create')
             ->nest('groupSelectField', 'group.selectField', ["groups"=>$groups, "selectedGroup"=>0])
             ->nest('studentSelectField', 'student.selectField', ["students"=>$students, "selectedStudent"=>0])
             ->nest('midyearRatingSelectField', 'groupStudent.ratingSelectField', ["ratings"=>$ratings, "selectedRating"=>'', "name"=>'midyear_rating'])
             ->nest('finalRatingSelectField', 'groupStudent.ratingSelectField', ["ratings"=>$ratings, "selectedRating"=>'', "name"=>'final_rating']);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
          'group_id' => 'required',
          'student_id' => 'required',
          'date_start' => 'required|date',
          'date_end' => 'required|date',
        ]);

        $groupStudent = new GroupStudent;
        $groupStudent->group_id = $request->group_id;
        $groupStudent->student_id = $request->student_id;
        $groupStudent->date_start  = $request->date_start ;
        $groupStudent->date_end = $request->date_end;
        $groupStudent->midyear_rating = $request->midyear_rating;
        $groupStudent->final_rating = $request->final_rating;
        $groupStudent->save();

        return redirect($request->history_view);
    }

    public function show(GroupStudent $grupa_uczniowie, GroupStudentRepository $groupStudentRepo)
    {
        $previous = $groupStudentRepo->previousRecordId($grupa_uczniowie->id);
        $next = $groupStudentRepo->nextRecordId($grupa_uczniowie->id);
        return view('groupStudent.show', ["groupStudent"=>$grupa_uczniowie, "previous"=>$previous, "next"=>$next]);
    }

    public function edit(GroupStudent $grupa_uczniowie, GroupRepository $groupRepo, StudentRepository $studentRepo)
    {
        $groups = $groupRepo->getAll();
        $students = $studentRepo->getAll();
        $ratings = array('1', '2', '3', '4', '5', '6', 'nieklasyfikowany/a','zwolniony/a');
        return view('groupStudent.edit', ["groupStudent"=>$grupa_uczniowie])
             ->nest('groupSelectField', 'group.selectField', ["groups"=>$groups, "selectedGroup"=>$grupa_uczniowie->group_id])
             ->nest('studentSelectField', 'student.selectField', ["students"=>$students, "selectedStudent"=>$grupa_uczniowie->student_id])
             ->nest('midyearRatingSelectField', 'groupStudent.ratingSelectField', ["ratings"=>$ratings, "selectedRating"=>$grupa_uczniowie->midyear_rating, "name"=>'midyear_rating'])
             ->nest('finalRatingSelectField', 'groupStudent.ratingSelectField', ["ratings"=>$ratings, "selectedRating"=>$grupa_uczniowie->final_rating, "name"=>'final_rating']);
    }

    public function update(Request $request, GroupStudent $grupa_uczniowie)
    {
        $this->validate($request, [
          'group_id' => 'required',
          'student_id' => 'required',
          'date_start' => 'required|date',
          'date_end' => 'required|date',
        ]);

        $grupa_uczniowie->group_id = $request->group_id;
        $grupa_uczniowie->student_id = $request->student_id;
        $grupa_uczniowie->date_start  = $request->date_start ;
        $grupa_uczniowie->date_end = $request->date_end;
        $grupa_uczniowie->midyear_rating = $request->midyear_rating;
        $grupa_uczniowie->final_rating = $request->final_rating;
        $grupa_uczniowie->save();

        return redirect($request->history_view);
    }

    public function destroy(GroupStudent $grupa_uczniowie)
    {
        $grupa_uczniowie->delete();
        return redirect( $_SERVER['HTTP_REFERER'] );
    }
}
