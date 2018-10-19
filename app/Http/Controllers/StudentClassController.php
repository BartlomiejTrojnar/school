<?php
namespace App\Http\Controllers;
use App\Models\StudentClass;
use App\Models\SchoolYear;
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
        if(isset($_GET['grade_id'])) $selectedGrade = $_GET['grade_id'];   else $selectedGrade = 0;
        if(isset($_GET['student_id'])) $selectedStudent = $_GET['student_id'];   else $selectedStudent = 0;
        return view('studentClass.create', ["proposedNumber"=>$proposedNumber, "proposedDates"=>$proposedDates])
             ->nest('studentSelectField', 'student.selectField', ["students"=>$students, "selectedStudent"=>$selectedStudent])
             ->nest('gradeSelectField', 'grade.selectField', ["grades"=>$grades, "selectedGrade"=>$selectedGrade]);
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
          'confirmation_date_start' => 'integer|between:0,1',
          'confirmation_date_end' => 'integer|between:0,1',
          'confirmation_numer' => 'integer|between:0,1',
          'confirmation_comments' => 'integer|between:0,1',
        ]);

        $studentClass = new StudentClass;
        $studentClass->student_id = $request->student_id;
        $studentClass->grade_id = $request->grade_id;
        $studentClass->date_start = $request->date_start;
        $studentClass->date_end = $request->date_end;
        $studentClass->numer = $request->numer;
        $studentClass->comments = $request->comments;
        $studentClass->confirmation_date_start = $request->confirmation_date_start == 'on' ? true : false;
        $studentClass->confirmation_date_end   = $request->confirmation_date_end   == 'on' ? true : false;
        $studentClass->confirmation_numer      = $request->confirmation_numer      == 'on' ? true : false;
        $studentClass->confirmation_comments   = $request->confirmation_comments   == 'on' ? true : false;
        $studentClass->save();

        return redirect(route('klasa.show', $studentClass->grade_id));
    }

    public function edit(StudentClass $klasy_uczniow, StudentRepository $studentRepo, GradeRepository $gradeRepo)
    {
        $dates = SchoolYear::getDatesOfSchoolYear(date('Y-m-d'));
        $students = $studentRepo->getAll();
        $grades = $gradeRepo->getAll();
        return view('studentClass.edit', ["studentClass"=>$klasy_uczniow, "dates"=>$dates])
             ->nest('studentSelectField', 'student.selectField', ["students"=>$students, "selectedStudent"=>$klasy_uczniow->student_id])
             ->nest('gradeSelectField', 'grade.selectField', ["grades"=>$grades, "selectedGrade"=>$klasy_uczniow->grade_id]);
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
          'confirmation_date_start' => 'integer|between:0,1',
          'confirmation_date_end' => 'integer|between:0,1',
          'confirmation_numer' => 'integer|between:0,1',
          'confirmation_comments' => 'integer|between:0,1',
        ]);

        $klasy_uczniow->student_id = $request->student_id;
        $klasy_uczniow->grade_id = $request->grade_id;
        $klasy_uczniow->date_start = $request->date_start;
        $klasy_uczniow->date_end = $request->date_end;
        $klasy_uczniow->numer = $request->numer;
        $klasy_uczniow->comments = $request->comments;
        $klasy_uczniow->confirmation_date_start = $request->confirmation_date_start == 'on' ? true : false;
        $klasy_uczniow->confirmation_date_end   = $request->confirmation_date_end   == 'on' ? true : false;
        $klasy_uczniow->confirmation_numer      = $request->confirmation_numer      == 'on' ? true : false;
        $klasy_uczniow->confirmation_comments   = $request->confirmation_comments   == 'on' ? true : false;
        $klasy_uczniow->save();

        return redirect($request->history_view);
    }

    public function destroy(StudentClass $klasy_uczniow)
    {
        $klasy_uczniow->delete();
        return redirect( $_SERVER['HTTP_REFERER'] );
    }
}
