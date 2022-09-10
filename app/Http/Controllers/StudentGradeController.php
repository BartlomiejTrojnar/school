<?php
// ------------------------ (C) mgr inż. Bartłomiej Trojnar; 10.09.2022 ------------------------ //
namespace App\Http\Controllers;
use App\Models\StudentGrade;
use App\Repositories\StudentGradeRepository;

use App\Repositories\GradeRepository;
use App\Repositories\StudentRepository;
use App\Repositories\SchoolYearRepository;
use Illuminate\Http\Request;

class StudentGradeController extends Controller
{
    public function orderBy($column) {
        if(session()->get('StudentGradeOrderBy[0]') == $column)
            if(session()->get('StudentGradeOrderBy[1]') == 'desc') session()->put('StudentGradeOrderBy[1]', 'asc');
            else session()->put('StudentGradeOrderBy[1]', 'desc');
        else {
            session()->put('StudentGradeOrderBy[4]', session()->get('StudentGradeOrderBy[2]'));
            session()->put('StudentGradeOrderBy[2]', session()->get('StudentGradeOrderBy[0]'));
            session()->put('StudentGradeOrderBy[0]', $column);
            session()->put('StudentGradeOrderBy[5]', session()->get('StudentGradeOrderBy[3]'));
            session()->put('StudentGradeOrderBy[3]', session()->get('StudentGradeOrderBy[1]'));
            session()->put('StudentGradeOrderBy[1]', 'asc');
        }
        return redirect( $_SERVER['HTTP_REFERER'] );
    }

    public function create(Request $request, StudentRepository $studentRepo, GradeRepository $gradeRepo, SchoolYearRepository $syRepo) {
        if( $request->version=="forStudent" )   return $this -> createForStudent($request->student_id, $studentRepo, $gradeRepo, $syRepo);
        if( $request->version=="forGrade" )     return $this -> createForGrade($request->grade_id, $gradeRepo, $studentRepo, $syRepo);
    }
/*
    private function createForGrade($grade_id, $gradeRepo, $studentRepo, $syRepo) {
        $grade = $gradeRepo -> find($grade_id);
        $studentSelected = session()->get('studentSelected');
        $students = $studentRepo -> getAllSorted();
        $studentSelectField = view('student.selectField', ["students"=>$students, "studentSelected"=>$studentSelected]);
        $lastRecord = StudentGrade::all() -> last();
        $proposedDate = $grade->year_of_graduation.'-01-01';
        $proposedDates = $syRepo -> getDatesOfSchoolYear($proposedDate);
        return view('studentGrade.createForGrade', ["grade"=>$grade, "studentSelectField"=>$studentSelectField, "lastRecord"=>$lastRecord, "proposedDates"=>$proposedDates]);
    }
*/
    private function createForStudent($student_id, $studentRepo, $gradeRepo, $syRepo) {
        $student = $studentRepo -> find($student_id);
        $lastRecord = StudentGrade::all() -> last();

        $grades = $gradeRepo -> getAllSorted();
        if( session()->get('gradeSelected') ) {
            $gradeSelected = session()->get('gradeSelected');
            $grade = $gradeRepo -> find($gradeSelected);
            $proposedDate = $grade->year_of_graduation.'-01-01';
        }
        else {
            $gradeSelected = $lastRecord->grade_id;
            $proposedDate = date('Y-m-d');
        }
        $gradeSelectField = view('grade.selectField', ["name"=>"grade_id", "grades"=>$grades, "gradeSelected"=>$gradeSelected]);

        $proposedDates = $syRepo -> getDatesOfSchoolYear($proposedDate);

        return view('studentGrade.createForStudent', ["student"=>$student, "gradeSelectField"=>$gradeSelectField, "lastRecord"=>$lastRecord, "proposedDates"=>$proposedDates]);
    }
/*
    public function addMany(StudentRepository $studentRepo, GradeRepository $gradeRepo, StudentGradeRepository $sgRepo) {
        $students = $studentRepo -> getAllOrderByLastName();
        $grade = $gradeRepo -> find( session()->get('gradeSelected') );
        $lastRecord = StudentGrade::all() -> last();
        $studentGrades = $sgRepo -> getGradeStudents( $grade->id );

        return view('StudentGrade.addMany', ["students"=>$students, "grade"=>$grade, "lastRecord"=>$lastRecord, "studentGrades"=>$studentGrades]);
    }
*/
    public function store(Request $request) {
        $this->validate($request, [
          'student_id' => 'required',
          'grade_id' => 'required',
          'start' => 'required|date',
          'end' => 'required|date',
        ]);

        $StudentGrade = new StudentGrade;
        $StudentGrade->student_id = $request->student_id;
        $StudentGrade->grade_id = $request->grade_id;
        $StudentGrade->start = $request->start;
        $StudentGrade->end = $request->end;
        $StudentGrade->confirmation_start = $request->confirmation_start == 'on' ? true : false;
          if( $request->confirmation_start == 1 ) $StudentGrade->confirmation_start = true;
        $StudentGrade->confirmation_end   = $request->confirmation_end   == 'on' ? true : false;
          if( $request->confirmation_end == 1 ) $StudentGrade->confirmation_end = true;
        $StudentGrade -> save();

        return $StudentGrade->id;
    }
/*
    public function createFromPreviousYear(Request $request, StudentGradeRepository $sgRepo) {
        $gradeSelected = session()->get('gradeSelected');
        $studentGrades = $sgRepo -> getGradeStudentsCompletedPreviousYear( $gradeSelected, $request->end );
        return view('StudentGrade.createFromPreviousYear', ["studentGrades"=>$studentGrades]);
    }

    public function storeFromPreviousYear(Request $request) {
        foreach($_POST as $key=>$value) {
            $studentGrade = new StudentGrade;
            if( substr($key, 0, 10) != 'student_id' ) continue;
            $id = substr($key, 10);
            $studentGrade->student_id = $value;
            $studentGrade->grade_id = $_POST['grade_id'.$id];
            $studentGrade->start = $_POST['start'.$id];
            $studentGrade->end = $_POST['end'.$id];
            $studentGrade->comments = $_POST['comments'.$id];
            if( isset($_POST['confirmationStart'.$id]) ) $studentGrade->confirmation_start=1;
              else $studentGrade->confirmation_start=0;
            if( isset($_POST['confirmationEnd'.$id]) ) $studentGrade->confirmation_end=1;
              else $studentGrade->confirmation_end=0;
            if( isset($_POST['confirmationComments'.$id]) ) $studentGrade->confirmation_comments=1;
              else $studentGrade->confirmation_comments=0;
            $studentGrade->save();
            unset($studentGrade);
        }
        return redirect($request->historyView);
    }
*/
    public function edit(Request $request, StudentGradeRepository $sgRepo, StudentRepository $studentRepo, GradeRepository $gradeRepo, SchoolYearRepository $syRepo) {
        if( $request->version=="forStudent" )   return $this -> editForStudent($request->id, $request->lp, $sgRepo, $gradeRepo, $syRepo);
        if( $request->version=="forGrade" )     return $this -> editForGrade($request->id, $sgRepo, $studentRepo, $syRepo);
        return $request->version;
    }

    private function editForGrade($id, $sgRepo, $studentRepo, $syRepo) {
        $studentGrade = $sgRepo -> find($id);
        $students = $studentRepo -> getAllSorted();
        $studentSelectField = view('student.selectField', ["students"=>$students, "studentSelected"=>$studentGrade->student_id]);
        $proposedDates = $syRepo -> getDatesOfSchoolYear( $studentGrade->end );
        return view('studentGrade.editForGrade', ["studentGrade"=>$studentGrade, "studentSelectField"=>$studentSelectField, "proposedDates"=>$proposedDates]);
    }

    private function editForStudent($id, $lp, $sgRepo, $gradeRepo, $syRepo) {
        $studentGrade = $sgRepo -> find($id);
        $grades = $gradeRepo -> getAllSorted();
        $gradeSelected = $studentGrade->grade_id;
        $gradeSelectField = view('grade.selectField', ["name"=>"grade_id", "grades"=>$grades, "gradeSelected"=>$gradeSelected]);
        $proposedDates = $syRepo -> getDatesOfSchoolYear( $studentGrade->end );
        return view('studentGrade.editForStudent', ["studentGrade"=>$studentGrade, "gradeSelectField"=>$gradeSelectField, "proposedDates"=>$proposedDates, "lp"=>$lp]);
    }

    public function editAll(Request $request) {
        $scRepo = new StudentGradeRepository(new StudentGrade);
        $grades[] = session()->get('gradeSelected');
        $studentGrades = $scRepo -> getStudentsFromGrades( $grades );
        print_r(count($studentGrades));
        return view('studentGrade.editAll', ["studentGrades"=>$studentGrades, "start"=>$request->start, "end"=>$request->end]);
    }

    public function update(Request $request, $id, StudentGrade $studentGrade) {
        $studentGrade = StudentGrade::find($id);
        $this->validate($request, [
          'student_id' => 'required',
          'grade_id' => 'required',
          'start' => 'required|date',
          'end' => 'required|date',
        ]);

        $studentGrade->student_id = $request->student_id;
        $studentGrade->grade_id = $request->grade_id;
        $studentGrade->start = $request->start;
        $studentGrade->end = $request->end;
        $studentGrade->confirmation_start = $request->confirmation_start;
        $studentGrade->confirmation_end   = $request->confirmation_end;
        $studentGrade -> save();
        return $id;
    }

    public function updateEnd(Request $request) {
        $studentGrade = StudentGrade::find($request->id);
        $studentGrade->end = $request->end;
        $studentGrade->confirmation_end = 1;
        $studentGrade -> save();
        return $studentGrade->id;
    }

    public function updateAll(Request $request, StudentGrade $studentGrade) {
        foreach($_POST as $key=>$value) {
            if( substr($key, 0, 5) != 'start' ) continue;
            $id = substr($key, 5);
            $studentGrade = StudentGrade::find($id);
            $studentGrade->start = $value;
            $studentGrade->end = $_POST['end'.$id];
            if( isset($_POST['confirmationStart'.$id]) ) $studentGrade->confirmation_start=1;
              else $studentGrade->confirmation_start=0;
            if( isset($_POST['confirmationEnd'.$id]) ) $studentGrade->confirmation_end=1;
              else $studentGrade->confirmation_end=0;
            $studentGrade -> save();
        }
        return redirect( $request->historyView."?start=$request->filterDateStart&end=$request->filterDateEnd" );
    }

    public function destroy($id, StudentGrade $studentGrade) {
        $studentGrade = $studentGrade -> find($id);
        $studentGrade -> delete();
    }

    public function refreshRow(Request $request, StudentGrade $studentGrade, SchoolYearRepository $schoolYearRepo) {
        $studentGrade = $studentGrade -> find($request->id);
        $schoolYearSelected = session()->get('schoolYearSelected');
        if($schoolYearSelected) {
            $schoolYear = $schoolYearRepo -> find($schoolYearSelected);
            $year = substr($schoolYear->end, 0, 4);
        }
        else $year=0;

        if( $request->version=="forGrade" )     return view('studentGrade.rowForGrade', ["studentGrade"=>$studentGrade]);
        if( $request->version=="forStudent" )   return view('studentGrade.rowForStudent', ["studentGrade"=>$studentGrade, "lp"=>$request->lp, "year"=>$year]);
        return $request->version;

        $studentGrade = $studentGrade -> find($request->id);
        return view('studentGrade.row', ["studentGrade"=>$studentGrade]);
    }
/*
    public function refreshTable(Request $request, StudentRepository $studentRepo, StudentGradeRepository $sgRepo, SchoolYearRepository $schoolYearRepo) {
        if($request->version=="forStudent") {
            $student = $studentRepo -> find($request->student_id);
            $subTitle = "Klasy ucznia";
            $studentGrades = $sgRepo -> getStudentGrades($student->id);
    
            $year = 0;
            if( !empty(session()->get('schoolYearSelected')) ) {
                $schoolYear = $schoolYearRepo -> find(session()->get('schoolYearSelected'));
                $year = substr($schoolYear->end, 0, 4);
            }
            $studentHistoriesView = "załaduj historię - funkcję trzeba dokończyć";
    
            // przygotowanie widoku z tabelą klas ucznia
            return view('studentGrade.tableForStudent', ["subTitle"=>$subTitle, "studentGrades"=>$studentGrades, "student"=>$student, "yearOfStudy"=>$year, "studentHistoriesView"=>$studentHistoriesView]);    
        }
    }
    */
}