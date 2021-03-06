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
    public function orderBy($column)
    {
        if(session()->get('StudentClassOrderBy[0]') == $column)
          if(session()->get('StudentClassOrderBy[1]') == 'desc')
            session()->put('StudentClassOrderBy[1]', 'asc');
          else
            session()->put('StudentClassOrderBy[1]', 'desc');
        else
        {
          session()->put('StudentClassOrderBy[4]', session()->get('StudentClassOrderBy[2]'));
          session()->put('StudentClassOrderBy[2]', session()->get('StudentClassOrderBy[0]'));
          session()->put('StudentClassOrderBy[0]', $column);
          session()->put('StudentClassOrderBy[5]', session()->get('StudentClassOrderBy[3]'));
          session()->put('StudentClassOrderBy[3]', session()->get('StudentClassOrderBy[1]'));
          session()->put('StudentClassOrderBy[1]', 'asc');
        }
        return redirect( $_SERVER['HTTP_REFERER'] );
    }

    public function create(StudentRepository $studentRepo, GradeRepository $gradeRepo, StudentClassRepository $scRepo, SchoolYearRepository $syRepo)
    {
        $proposedNumber = $scRepo -> getLastNumber() + 1;
        $proposedDates = $syRepo -> getDatesOfSchoolYear(date('Y-m-d'));
        $lastRecord = StudentClass::all()->last();
        $students = $studentRepo->getAllSorted();
        $grades = $gradeRepo -> getAllSorted();
        if(isset($_GET['grade_id'])) $gradeSelected = $_GET['grade_id'];   else $gradeSelected = $lastRecord->grade_id;
        if(isset($_GET['student_id'])) $studentSelected = $_GET['student_id'];   else $studentSelected = 0;
        return view('studentClass.create', ["proposedNumber"=>$proposedNumber, "proposedDates"=>$proposedDates, "lastRecord"=>$lastRecord])
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
        $students = $studentRepo->getAllSorted();
        $grades = $gradeRepo->getAllSorted();
        $studentSelected = $studentClass->student_id;
        $gradeSelected = $studentClass->grade_id;

        return view('studentClass.edit', ["studentClass"=>$studentClass, "proposedDates"=>$proposedDates])
             ->nest('studentSelectField', 'student.selectField', ["students"=>$students, "studentSelected"=>$studentSelected])
             ->nest('gradeSelectField', 'grade.selectField', ["grades"=>$grades, "gradeSelected"=>$gradeSelected]);
    }

    public function editAll(StudentClassRepository $scRepo)
    {
        $studentClasses = $scRepo -> getGradeStudents( $_GET['grade_id'] );

        return view('studentClass.editAll', ["studentClasses"=>$studentClasses, "date_start"=>$_GET['date_start'], "date_end"=>$_GET['date_end']]);
    }

    public function update(Request $request, $id, StudentClass $klasy_ucznia)
    {
        $klasy_ucznia = StudentClass::find($id);
        $this->validate($request, [
          'student_id' => 'required',
          'grade_id' => 'required',
          'date_start' => 'required|date',
          'date_end' => 'required|date',
          'number' => 'required|integer|between:1,99',
          'comments' => 'max:32',
        ]);

        $klasy_ucznia->student_id = $request->student_id;
        $klasy_ucznia->grade_id = $request->grade_id;
        $klasy_ucznia->date_start = $request->date_start;
        $klasy_ucznia->date_end = $request->date_end;
        $klasy_ucznia->number = $request->number;
        $klasy_ucznia->comments = $request->comments;
        $klasy_ucznia->confirmation_date_start = $request->confirmation_date_start == 'on' ? true : false;
        $klasy_ucznia->confirmation_date_end   = $request->confirmation_date_end   == 'on' ? true : false;
        $klasy_ucznia->confirmation_numer      = $request->confirmation_numer      == 'on' ? true : false;
        $klasy_ucznia->confirmation_comments   = $request->confirmation_comments   == 'on' ? true : false;
        $klasy_ucznia->save();

        return redirect($request->historyView);
    }

    public function updateAll(Request $request, StudentClass $klasy_ucznia)
    {
        foreach($_GET as $key=>$value) {
            if( substr($key, 0, 10) != 'date_start' ) continue;
            $id = substr($key, 10);
            $klasy_ucznia = StudentClass::find($id);
            $klasy_ucznia->date_start = $value;
            $klasy_ucznia->date_end = $_GET['date_end'.$id];
            $klasy_ucznia->number = $_GET['number'.$id];
            $klasy_ucznia->comments = $_GET['comments'.$id];
            if( isset($_GET['confirmation_date_start'.$id]) ) $klasy_ucznia->confirmation_date_start=1;
            if( isset($_GET['confirmation_date_end'.$id]) ) $klasy_ucznia->confirmation_date_end=1;
            if( isset($_GET['confirmation_number'.$id]) ) $klasy_ucznia->confirmation_numer=1;
            if( isset($_GET['confirmation_comments'.$id]) ) $klasy_ucznia->confirmation_comments=1;;
            $klasy_ucznia->save();
        }
        return redirect($request->historyView);
    }

    public function updateNumber(Request $request, StudentClass $klasy_ucznia)
    {
        $klasy_ucznia = StudentClass::find($request->id);
        $klasy_ucznia->number = $request->number;
        $klasy_ucznia->save();
        return 0;
    }

    public function destroy($id)
    {
        StudentClass::destroy($id);
        return redirect( $_SERVER['HTTP_REFERER'] );
    }
}
