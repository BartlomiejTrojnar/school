<?php
// ------------------------ (C) mgr inż. Bartłomiej Trojnar; 18.01.2023 ------------------------ //
namespace App\Http\Controllers;
use App\Models\BookOfStudent;
use App\Repositories\BookOfStudentRepository;

use App\Repositories\SchoolRepository;
use App\Repositories\StudentRepository;
use Illuminate\Http\Request;

class BookOfStudentController extends Controller
{
    public function create(Request $request, BookOfStudentRepository $bookOfStudentRepo, SchoolRepository $schoolRepo, StudentRepository $studentRepo) {
        if( $request->version=="forStudent" )   return $this -> createForStudent($bookOfStudentRepo, $schoolRepo, $request->student_id);
        return $this -> createForIndex($bookOfStudentRepo, $schoolRepo, $studentRepo);
    }

    private function createForIndex($bookOfStudentRepo, $schoolRepo, $studentRepo) {
        $schools = $schoolRepo->getAllSorted();
        $schoolSelected = session()->get('schoolSelected');
        $schoolSF = view('school.selectField', ["schools"=>$schools, "schoolSelected"=>$schoolSelected]);
        $students = $studentRepo->getAllSorted();
        $studentSelected = session()->get('studentSelected');
        $studentSF = view('student.selectField', ["students"=>$students, "studentSelected"=>$studentSelected]);
        $proposedNumber = $bookOfStudentRepo -> getLastNumber() + 1;
        return view('bookOfStudent.create', ["schoolSF"=>$schoolSF, "studentSF"=>$studentSF, "proposedNumber"=>$proposedNumber]);
    }

    public function store(Request $request) {
        $this->validate($request, [ 'school_id' => 'required', 'student_id' => 'required', 'number' => 'required|integer', ]);
        $bookOfStudent = new BookOfStudent;
        $bookOfStudent->school_id = $request->school_id;
        $bookOfStudent->student_id = $request->student_id;
        $bookOfStudent->number = $request->number;
        $bookOfStudent -> save();
        return $bookOfStudent->id;
    }

    public function edit(Request $request, BookOfStudent $bookOfStudent, SchoolRepository $schoolRepo, StudentRepository $studentRepo) {
        if( $request->version == "forStudent" ) return $this -> editForStudent($request->id, $schoolRepo);
        return $this -> editForIndex($request->id, $request->lp, $bookOfStudent, $schoolRepo, $studentRepo);
    }

    private function editForIndex($id, $lp, $bookOfStudent, $schoolRepo, $studentRepo) {
        $bookOfStudent = $bookOfStudent -> find($id);
        $schools = $schoolRepo -> getAllSorted();
        $schoolSF = view('school.selectField', ["schools"=>$schools, "schoolSelected"=>$bookOfStudent->school_id]);
        $students = $studentRepo -> getAllSorted();
        $studentSF = view('student.selectField', ["students"=>$students, "studentSelected"=>$bookOfStudent->student_id]);
        return view('bookOfStudent.edit', ["bookOfStudent"=>$bookOfStudent, "schoolSF"=>$schoolSF, "studentSF"=>$studentSF, "lp"=>$lp]);
    }

    public function update($id, Request $request, BookOfStudent $bookOfStudent) {
        $bookOfStudent = $bookOfStudent -> find($id);
        $this->validate($request, [ 'school_id' => 'required', 'student_id' => 'required', 'number' => 'required|integer', ]);
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
        return view('bookOfStudent.row', ["bookOfStudent"=>$bookOfStudent, "lp"=>$request->lp]);
    }

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
        $schoolSF = view('school.selectField', ["schools"=>$schools, "schoolSelected"=>$schoolSelected]);
        return view('bookOfStudent.index', ["bookOfStudents"=>$bookOfStudents, "schoolSF"=>$schoolSF]);
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
/*


    private function createForStudent($bookOfStudentRepo, $schoolRepo, $student_id) {
        $schools = $schoolRepo->getAllSorted();
        $schoolSelected = session()->get('schoolSelected');
        $schoolSelectField = view('school.selectField', ["schools"=>$schools, "schoolSelected"=>$schoolSelected]);
        $proposedNumber = $bookOfStudentRepo -> getLastNumber() + 1;
        
        return view('bookOfStudent.createForStudent', ["proposedNumber"=>$proposedNumber, "schoolSelectField"=>$schoolSelectField, "student_id"=>$student_id]);
    }

    private function editForStudent($id, $schoolRepo) {
        $bookOfStudent = new BookOfStudent;
        $bookOfStudent = $bookOfStudent -> find($id);
        $schools = $schoolRepo -> getAllSorted();
        $schoolSelectField = view('school.selectField', ["schools"=>$schools, "schoolSelected"=>$bookOfStudent->school_id]);
        return view('bookOfStudent.editForStudent', ["bookOfStudent"=>$bookOfStudent, "schoolSelectField"=>$schoolSelectField]);
    }

*/
}