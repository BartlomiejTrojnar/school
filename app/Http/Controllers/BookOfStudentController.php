<?php
namespace App\Http\Controllers;
use App\Models\BookOfStudent;
use App\Repositories\BookOfStudentRepository;

use App\Repositories\SchoolRepository;
use App\Repositories\StudentRepository;
use Illuminate\Http\Request;

class BookOfStudentController extends Controller
{
    public function index(BookOfStudentRepository $bookOfStudentRepo, SchoolRepository $schoolRepo)
    {
        $bookOfStudents = $bookOfStudentRepo->getAllSorted();
        $schools = $schoolRepo->getAllSorted();
        return view('bookOfStudent.index', ["bookOfStudents"=>$bookOfStudents])
            -> nest('schoolSelectField', 'school.selectField', ["schools"=>$schools, "schoolSelected"=>1]);
    }

    public function orderBy($column)
    {
        if(session()->get('BookOrderBy[0]') == $column)
          if(session()->get('BookOrderBy[1]') == 'desc')
            session()->put('BookOrderBy[1]', 'asc');
          else
            session()->put('BookOrderBy[1]', 'desc');
        else
        {
          session()->put('BookOrderBy[4]', session()->get('BookOrderBy[2]'));
          session()->put('BookOrderBy[2]', session()->get('BookOrderBy[0]'));
          session()->put('BookOrderBy[0]', $column);
          session()->put('BookOrderBy[5]', session()->get('BookOrderBy[3]'));
          session()->put('BookOrderBy[3]', session()->get('BookOrderBy[1]'));
          session()->put('BookOrderBy[1]', 'asc');
        }
        return redirect( route('ksiega_uczniow.index') );
    }

    public function create(SchoolRepository $schoolRepo, StudentRepository $studentRepo)
    {
        $schools = $schoolRepo->getAll();
        $students = $studentRepo->getAll();
        return view('bookOfStudent.create')
             ->nest('schoolSelectField', 'school.selectField', ["schools"=>$schools, "schoolSelected"=>1])
             ->nest('studentSelectField', 'student.selectField', ["students"=>$students, "studentSelected"=>0]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
          'school_id' => 'required',
          'student_id' => 'required',
          'number' => 'required|integer',
        ]);

        $bookOfStudent = new BookOfStudent;
        $bookOfStudent->school_id = $request->school_id;
        $bookOfStudent->student_id = $request->student_id;
        $bookOfStudent->number = $request->number;
        $bookOfStudent->save();

        return redirect($request->history_view);
    }

    public function edit(BookOfStudent $ksiega_uczniow, SchoolRepository $schoolRepo, StudentRepository $studentRepo)
    {
        $schools = $schoolRepo->getAll();
        $students = $studentRepo->getAll();
        return view('bookOfStudent.edit', ["bookOfStudent"=>$ksiega_uczniow])
             ->nest('schoolSelectField', 'school.selectField', ["schools"=>$schools, "schoolSelected"=>$ksiega_uczniow->school_id])
             ->nest('studentSelectField', 'student.selectField', ["students"=>$students, "studentSelected"=>$ksiega_uczniow->student_id]);
    }

    public function update(Request $request, BookOfStudent $ksiega_uczniow)
    {
        $this->validate($request, [
          'school_id' => 'required',
          'student_id' => 'required',
          'number' => 'required|integer',
        ]);

        $ksiega_uczniow->school_id = $request->school_id;
        $ksiega_uczniow->student_id = $request->student_id;
        $ksiega_uczniow->number = $request->number;
        $ksiega_uczniow->save();

        return redirect($request->history_view);
    }

    public function destroy(BookOfStudent $ksiega_uczniow)
    {
        $ksiega_uczniow->delete();
        return redirect( $_SERVER['HTTP_REFERER'] );
    }
}
