<?php
// ------------------------ (C) mgr inż. Bartłomiej Trojnar; 05.01.2022 ------------------------ //
namespace App\Http\Controllers;
use App\Models\BookOfStudent;
use App\Repositories\BookOfStudentRepository;

use App\Repositories\SchoolRepository;
use App\Repositories\StudentRepository;
use Illuminate\Http\Request;

class BookOfStudentController extends Controller
{
    public function index(BookOfStudentRepository $bookOfStudentRepo, SchoolRepository $schoolRepo) {
        if(isset($_GET['page']))  session()->put('bookOfStudentsPage', $_GET['page']);
        else session()->put('bookOfStudentsPage', 1);
        $schools = $schoolRepo->getAllSorted();
        $schoolSelected = session()->get('schoolSelected');
        $bookOfStudents = $bookOfStudentRepo -> getAllSortedAndPaginate();
        if( $schoolSelected ) {
            $bookOfStudents = BookOfStudent::where('school_id', $schoolSelected);
            $bookOfStudents = $bookOfStudentRepo -> sortAndPaginateRecords($bookOfStudents);
        }
        $schoolSelectField = view('school.selectField', ["schools"=>$schools, "schoolSelected"=>$schoolSelected]);
        return view('bookOfStudent.index', ["bookOfStudents"=>$bookOfStudents, "schoolSelectField"=>$schoolSelectField]);
    }

    public function orderBy($column) {
        if(session()->get('BookOfStudentOrderBy[0]') == $column)
            if(session()->get('BookOfStudentOrderBy[1]') == 'desc')  session()->put('BookOfStudentOrderBy[1]', 'asc');
            else  session()->put('BookOfStudentOrderBy[1]', 'desc');
        else {
            session()->put('BookOfStudentOrderBy[4]', session()->get('BookOfStudentOrderBy[2]'));
            session()->put('BookOfStudentOrderBy[2]', session()->get('BookOfStudentOrderBy[0]'));
            session()->put('BookOfStudentOrderBy[0]', $column);
            session()->put('BookOfStudentOrderBy[5]', session()->get('BookOfStudentOrderBy[3]'));
            session()->put('BookOfStudentOrderBy[3]', session()->get('BookOfStudentOrderBy[1]'));
            session()->put('BookOfStudentOrderBy[1]', 'asc');
        }
        return redirect( route('ksiega_uczniow.index') );
    }

    public function create(Request $request, BookOfStudentRepository $bookOfStudentRepo, SchoolRepository $schoolRepo, StudentRepository $studentRepo) {
        if( $request->version=="forIndex" )     return $this -> createForIndex($bookOfStudentRepo, $schoolRepo, $studentRepo);
        if( $request->version=="forStudent" )   return $this -> createForStudent($bookOfStudentRepo, $schoolRepo, $request->student_id);
        return $request->version;
    }

    private function createForIndex($bookOfStudentRepo, $schoolRepo, $studentRepo) {
        $schools = $schoolRepo->getAllSorted();
        $schoolSelected = session()->get('schoolSelected');
        $schoolSelectField = view('school.selectField', ["schools"=>$schools, "schoolSelected"=>$schoolSelected]);
        $students = $studentRepo->getAllSorted();
        $studentSelected = session()->get('studentSelected');
        $studentSelectField = view('student.selectField', ["students"=>$students, "studentSelected"=>$studentSelected]);
        $proposedNumber = $bookOfStudentRepo -> getLastNumber() + 1;
        return view('bookOfStudent.create', ["schoolSelectField"=>$schoolSelectField, "studentSelectField"=>$studentSelectField, "proposedNumber"=>$proposedNumber]);
    }

    private function createForStudent($bookOfStudentRepo, $schoolRepo, $student_id) {
        $schools = $schoolRepo->getAllSorted();
        $schoolSelected = session()->get('schoolSelected');
        $schoolSelectField = view('school.selectField', ["schools"=>$schools, "schoolSelected"=>$schoolSelected]);
        $proposedNumber = $bookOfStudentRepo -> getLastNumber() + 1;
        
        return view('bookOfStudent.createForStudent', ["proposedNumber"=>$proposedNumber, "schoolSelectField"=>$schoolSelectField, "student_id"=>$student_id]);
    }

    public function store(Request $request) {
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

        return $bookOfStudent->id;
    }

    public function edit(Request $request, BookOfStudent $bookOfStudent, SchoolRepository $schoolRepo, StudentRepository $studentRepo) {
        if( $request->version == "forIndex" )   return $this -> editForIndex($request->id, $bookOfStudent, $schoolRepo, $studentRepo);
        if( $request->version == "forStudent" ) return $this -> editForStudent($request->id, $schoolRepo);
    }

    private function editForIndex($id, $bookOfStudent, $schoolRepo, $studentRepo) {
        $bookOfStudent = $bookOfStudent -> find($id);
        $schools = $schoolRepo -> getAllSorted();
        $schoolSelectField = view('school.selectField', ["schools"=>$schools, "schoolSelected"=>$bookOfStudent->school_id]);
        $students = $studentRepo -> getAllSorted();
        $studentSelectField = view('student.selectField', ["students"=>$students, "studentSelected"=>$bookOfStudent->student_id]);
        return view('bookOfStudent.edit', ["bookOfStudent"=>$bookOfStudent, "schoolSelectField"=>$schoolSelectField, "studentSelectField"=>$studentSelectField]);
    }

    private function editForStudent($id, $schoolRepo) {
        $bookOfStudent = new BookOfStudent;
        $bookOfStudent = $bookOfStudent -> find($id);
        $schools = $schoolRepo -> getAllSorted();
        $schoolSelectField = view('school.selectField', ["schools"=>$schools, "schoolSelected"=>$bookOfStudent->school_id]);
        return view('bookOfStudent.editForStudent', ["bookOfStudent"=>$bookOfStudent, "schoolSelectField"=>$schoolSelectField]);
    }

    public function update($id, Request $request, BookOfStudent $bookOfStudent) {
        $bookOfStudent = $bookOfStudent -> find($id);
        $this->validate($request, [
          'school_id' => 'required',
          'student_id' => 'required',
          'number' => 'required|integer',
        ]);

        $bookOfStudent->school_id = $request->school_id;
        $bookOfStudent->student_id = $request->student_id;
        $bookOfStudent->number = $request->number;
        $bookOfStudent -> save();

        return $bookOfStudent->id;
    }

    public function destroy($id, BookOfStudent $bookOfStudent) {
        $bookOfStudent = $bookOfStudent -> find($id);
        $bookOfStudent -> delete();
        return 1;
    }

    public function refreshRow(Request $request, BookOfStudent $bookOfStudent) {
        $bookOfStudent = $bookOfStudent -> find($request->id);
        if($request->version = "forIndex")  return view('bookOfStudent.row', ["bookOfStudent"=>$bookOfStudent, "lp"=>$request->lp]);
        return $request->version;
    }
}
