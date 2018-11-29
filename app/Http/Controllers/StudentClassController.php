<?php
namespace App\Http\Controllers;
use App\Models\StudentClass;
use App\Repositories\StudentClassRepository;

use App\Repositories\GradeRepository;
use App\Repositories\StudentRepository;
use App\Repositories\SchoolYearRepository;
use Illuminate\Http\Request;

class StudentClassController extends Controller
{
    public function create(StudentRepository $studentRepo, GradeRepository $gradeRepo, StudentClassRepository $scRepo, SchoolYearRepository $syRepo)
    {
        $proposedNumber = $scRepo->getLastNumber();
        $proposedDates = $syRepo->getDatesOfSchoolYear(date('Y-m-d'));
        $students = $studentRepo->getAll();
        $grades = $gradeRepo->getAll();
        if(isset($_GET['grade_id'])) $gradeSelected = $_GET['grade_id'];   else $gradeSelected = 0;
        if(isset($_GET['student_id'])) $studentSelected = $_GET['student_id'];   else $studentSelected = 0;
        return view('studentClass.create', ["proposedNumber"=>$proposedNumber, "proposedDates"=>$proposedDates])
             ->nest('studentSelectField', 'student.selectField', ["students"=>$students, "studentSelected"=>$studentSelected])
             ->nest('gradeSelectField', 'grade.selectField', ["grades"=>$grades, "gradeSelected"=>$gradeSelected]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
          'student_id' => 'required',
          'grade_id' => 'required',
          'date_start' => 'required|date',
          'date_end' => 'required|date',
          'number' => 'required|integer|between:1,99',
          'comments' => 'max:32',
        ]);

        $studentClass = new StudentClass;
        $studentClass->student_id = $request->student_id;
        $studentClass->grade_id = $request->grade_id;
        $studentClass->date_start = $request->date_start;
        $studentClass->date_end = $request->date_end;
        $studentClass->number = $request->number;
        $studentClass->comments = $request->comments;
        $studentClass->confirmation_date_start = $request->confirmation_date_start == 'on' ? true : false;
        $studentClass->confirmation_date_end   = $request->confirmation_date_end   == 'on' ? true : false;
        $studentClass->confirmation_numer      = $request->confirmation_numer      == 'on' ? true : false;
        $studentClass->confirmation_comments   = $request->confirmation_comments   == 'on' ? true : false;
        $studentClass->save();

        return redirect( $request->history_view );
    }

    public function edit($id, StudentClassRepository $scRepo, StudentRepository $studentRepo, GradeRepository $gradeRepo, SchoolYearRepository $syRepo)
    {
        $studentClass = $scRepo -> find($id);
        $proposedDates = $syRepo->getDatesOfSchoolYear(date('Y-m-d'));
        $students = $studentRepo->getAll();
        $grades = $gradeRepo->getAll();
        $studentSelected = $studentClass->student_id;
        $gradeSelected = $studentClass->grade_id;

        return view('studentClass.edit', ["studentClass"=>$studentClass, "proposedDates"=>$proposedDates])
             ->nest('studentSelectField', 'student.selectField', ["students"=>$students, "studentSelected"=>$studentSelected])
             ->nest('gradeSelectField', 'grade.selectField', ["grades"=>$grades, "gradeSelected"=>$gradeSelected]);
    }

    public function update(Request $request, StudentClass $klasy_uczniow)
    {
        $this->validate($request, [
          'student_id' => 'required',
          'grade_id' => 'required',
          'date_start' => 'required|date',
          'date_end' => 'required|date',
          'number' => 'required|integer|between:1,99',
          'comments' => 'max:32',
        ]);

        $klasy_uczniow->student_id = $request->student_id;
        $klasy_uczniow->grade_id = $request->grade_id;
        $klasy_uczniow->date_start = $request->date_start;
        $klasy_uczniow->date_end = $request->date_end;
        $klasy_uczniow->number = $request->number;
        $klasy_uczniow->comments = $request->comments;
        $klasy_uczniow->confirmation_date_start = $request->confirmation_date_start == 'on' ? true : false;
        $klasy_uczniow->confirmation_date_end   = $request->confirmation_date_end   == 'on' ? true : false;
        $klasy_uczniow->confirmation_numer      = $request->confirmation_numer      == 'on' ? true : false;
        $klasy_uczniow->confirmation_comments   = $request->confirmation_comments   == 'on' ? true : false;
        $klasy_uczniow->save();

        return redirect($request->historyView);
    }

    public function destroy($id)
    {
        StudentClass::destroy($id);
        return redirect( $_SERVER['HTTP_REFERER'] );
    }
}
