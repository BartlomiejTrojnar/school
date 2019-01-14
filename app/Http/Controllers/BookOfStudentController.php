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
        $schools = $schoolRepo->getAllSorted();
        $schoolSelected = session()->get('schoolSelected');

        $bookOfStudents = $bookOfStudentRepo -> getAllSorted();
        if( $schoolSelected ) {
            $bookOfStudents = BookOfStudent::where('school_id', $schoolSelected);
            $bookOfStudents = $bookOfStudentRepo -> sortAndPaginateRecords($bookOfStudents);
        }

        return view('bookOfStudent.index', ["bookOfStudents"=>$bookOfStudents])
            -> nest('schoolSelectField', 'school.selectField', ["schools"=>$schools, "schoolSelected"=>$schoolSelected]);
    }

    public function orderBy($column)
    {
        if(session()->get('BookOfStudentOrderBy[0]') == $column)
          if(session()->get('BookOfStudentOrderBy[1]') == 'desc')
            session()->put('BookOfStudentOrderBy[1]', 'asc');
          else
            session()->put('BookOfStudentOrderBy[1]', 'desc');
        else
        {
          session()->put('BookOfStudentOrderBy[4]', session()->get('BookOfStudentOrderBy[2]'));
          session()->put('BookOfStudentOrderBy[2]', session()->get('BookOfStudentOrderBy[0]'));
          session()->put('BookOfStudentOrderBy[0]', $column);
          session()->put('BookOfStudentOrderBy[5]', session()->get('BookOfStudentOrderBy[3]'));
          session()->put('BookOfStudentOrderBy[3]', session()->get('BookOfStudentOrderBy[1]'));
          session()->put('BookOfStudentOrderBy[1]', 'asc');
        }
        return redirect( route('ksiega_uczniow.index') );
    }

    public function create(SchoolRepository $schoolRepo, StudentRepository $studentRepo)
    {
        $schools = $schoolRepo->getAllSorted();
        $schoolSelected = session()->get('schoolSelected');
        $students = $studentRepo->getAllSorted();
        $studentSelected = session()->get('studentSelected');

        return view('bookOfStudent.create')
             ->nest('schoolSelectField', 'school.selectField', ["schools"=>$schools, "schoolSelected"=>$schoolSelected])
             ->nest('studentSelectField', 'student.selectField', ["students"=>$students, "studentSelected"=>$studentSelected]);
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
        $schools = $schoolRepo->getAllSorted();
        $students = $studentRepo->getAllSorted();
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
