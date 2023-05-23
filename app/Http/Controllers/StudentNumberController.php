<?php
// ------------------------ (C) mgr inż. Bartłomiej Trojnar; 23.05.2023 ------------------------ //
namespace App\Http\Controllers;

use App\Models\StudentNumber;
use App\Repositories\StudentNumberRepository;

use App\Repositories\GradeRepository;
use App\Repositories\StudentGradeRepository;
use App\Repositories\SchoolYearRepository;
use Illuminate\Http\Request;

class StudentNumberController extends Controller
{
    public function orderBy($column) {
        if(session()->get('StudentNumberOrderBy[0]') == $column)
            if(session()->get('StudentNumberOrderBy[1]') == 'desc')  session()->put('StudentNumberOrderBy[1]', 'asc');
            else  session()->put('StudentNumberOrderBy[1]', 'desc');
        else {
            session()->put('StudentNumberOrderBy[2]', session()->get('StudentNumberOrderBy[0]'));
            session()->put('StudentNumberOrderBy[0]', $column);
            session()->put('StudentNumberOrderBy[3]', session()->get('StudentNumberOrderBy[1]'));
            session()->put('StudentNumberOrderBy[1]', 'asc');
        }
        return redirect( $_SERVER['HTTP_REFERER'] );
    }

    public function create(Request $request, StudentNumberRepository $snRepo, GradeRepository $gradeRepo, StudentGradeRepository $studentGradeRepo, SchoolYearRepository $syRepo) {
        if( $request->version=="forStudent" )   return $this -> createForStudent($request->student_id, $snRepo, $gradeRepo, $syRepo);
        if( $request->version=="forGrade" )     return $this -> createForGrade($request->grade_id, $snRepo, $studentGradeRepo, $syRepo);
        return $request->version;
    }

    public function createForGrade($grade_id, $snRepo, $studentGradeRepo, $syRepo) {
        $grades[] = $grade_id;
        $studentGrades = $studentGradeRepo -> getStudentsFromGrades($grades, "");
        $students = [];
        foreach($studentGrades as $studentGrade) $students[] = $studentGrade->student;
        $studentSelected = session()->get('studentSelected');
        $studentSelectField = view('student.selectField', ["name"=>"student_id", "students"=>$students, "studentSelected"=>$studentSelected]);
        $schoolYears = $syRepo -> getAllSorted();
        $schoolYear = session()->get('schoolYearSelected');
        $schoolYearSelectField = view('schoolYear.selectField', ["schoolYears"=>$schoolYears, "schoolYearSelected"=>$schoolYear, "name"=>"school_year_id"]);
        $proposedNumber = $snRepo -> getLastNumber() + 1;
        return view('studentNumber.createForGrade', ["grade_id"=>$grade_id, "studentSelectField"=>$studentSelectField, "schoolYearSelectField"=>$schoolYearSelectField, "proposedNumber"=>$proposedNumber]);
    }

    public function createForStudent($student_id, $snRepo, $gradeRepo, $syRepo) {
        $schoolYear = session()->get('schoolYearSelected');
        $grades = $gradeRepo -> getAllSorted();
        $gradeSelected = session()->get('gradeSelected');
        $gradeSF = view('grade.selectField', ["name"=>"grade_id", "grades"=>$grades, "gradeSelected"=>$gradeSelected, "year"=>$schoolYear+1900]);

        $schoolYears = $syRepo -> getAllSorted();
        $schoolYear = session()->get('schoolYearSelected');
        $schoolYearSF = view('schoolYear.selectField', ["schoolYears"=>$schoolYears, "schoolYearSelected"=>$schoolYear, "name"=>"school_year_id"]);

        $proposedNumber = $snRepo -> getLastNumber() + 1;

        return view('studentNumber.createForStudent', ["student_id"=>$student_id, "gradeSF"=>$gradeSF,
            "schoolYearSF"=>$schoolYearSF, "proposedNumber"=>$proposedNumber]);
    }

    public function store(Request $request) {
        $this->validate($request, [  'student_id' => 'required', 'grade_id' => 'required', 'school_year_id' => 'required', 'number' => 'required|integer|between:1,99', ]);
        $StudentNumber = new StudentNumber;
        $StudentNumber->student_id = $request->student_id;
        $StudentNumber->grade_id = $request->grade_id;
        $StudentNumber->school_year_id = $request->school_year_id;
        $StudentNumber->number = $request->number;
        $StudentNumber->confirmation_number = $request->confirmationNumber;
        $StudentNumber -> save();
        return $StudentNumber->id;
    }

    public function copy(Request $request, StudentNumberRepository $studentNumberRepo) {
        $studentNumbers = $studentNumberRepo -> getGradeNumbersForSchoolYear($request->grade_id, $request->schoolYear_id);
        if(count($studentNumbers)) return 'Istnieją już numery w tym roku szkolnym';
        $studentNumbers = $studentNumberRepo -> getGradeNumbersForSchoolYear($request->grade_id, $request->schoolYear_id-1);
        foreach($studentNumbers as $sn) {
            $studentNumber = new StudentNumber;
            $studentNumber->student_id = $sn->student_id;
            $studentNumber->grade_id = $sn->grade_id;
            $studentNumber->school_year_id = $sn->school_year_id+1;
            $studentNumber->number = $sn->number;
            $studentNumber->confirmation_number = false;
            $studentNumber -> save();
        }
        return 1;
    }

    public function addNumbersForGrade(Request $request, StudentGradeRepository $studentGradeRepo, SchoolYearRepository $schoolYearRepo, GradeRepository $gradeRepo) {
        // ustalenie klasy dla uczniów
        $grade = $gradeRepo->find($request->grade_id);
        $grades[] = $grade->id;
        // ustalenie daty początku i końca klasy w roku szkolnym
        $year = session()->get('schoolYearSelected')+1900;
        $datka = $year.'-01-01';
        $datesOfSchoolYear = $schoolYearRepo->getDatesOfSchoolYear($datka);
        $start = $datesOfSchoolYear['dateOfStartSchoolYear'];
        $end = $datesOfSchoolYear['dateOfGraduationSchoolYear'];
        if($year==$grade->year_of_graduation)   $end = $datesOfSchoolYear['dateOfGraduationOfTheLastGrade'];
        // pobranie uczniów z wybranego roku szkolnego
        $studentGrades = $studentGradeRepo -> getStudentsFromGradesOrderByLastName($grades, $start, $end);
        $number = 1;
        foreach ($studentGrades as $studentGrade) {
            if($studentGrade->start <= $end && $studentGrade->end >= $end) {
                print_r($studentGrade->student->last_name);
                $studentNumber = new StudentNumber;
                $studentNumber->student_id = $studentGrade->student_id;
                $studentNumber->grade_id = $request->grade_id;
                $studentNumber->school_year_id = $request->schoolYear_id;
                $studentNumber->number = $number++;
                $studentNumber->confirmation_number = false;
                $studentNumber -> save();    
            }
        }
        return 1;
    }

    public function edit(Request $request, StudentNumberRepository $snRepo, GradeRepository $gradeRepo, StudentGradeRepository $studentGradeRepo, SchoolYearRepository $syRepo) {
        if( $request->version=="forStudent" )   return $this -> editForStudent($request->id, $snRepo, $gradeRepo, $syRepo);
        if( $request->version=="forGrade" )     return $this -> editForGrade($request->id, $snRepo, $studentGradeRepo, $syRepo);
        return $request->version;
    }

    public function editForGrade($id, $snRepo, $studentGradeRepo, $syRepo) {
        $studentNumber = $snRepo -> find($id);
        $grades[] = session()->get('gradeSelected');
        $studentGrades = $studentGradeRepo -> getStudentsFromGrades($grades, "");
        $students = [];
        foreach($studentGrades as $studentGrade) $students[] = $studentGrade->student;
        $studentSelected = $studentNumber->student_id;
        $studentSelectField = view('student.selectField', ["name"=>"student_id", "students"=>$students, "studentSelected"=>$studentSelected]);
        $schoolYears = $syRepo -> getAllSorted();
        $schoolYearSelected = $studentNumber->school_year_id;
        $schoolYearSelectField = view('schoolYear.selectField', ["schoolYears"=>$schoolYears, "schoolYearSelected"=>$schoolYearSelected, "name"=>"school_year_id"]);
        return view('studentNumber.editForGrade', ["studentNumber"=>$studentNumber, "studentSelectField"=>$studentSelectField, "schoolYearSelectField"=>$schoolYearSelectField]);
    }

    public function editForStudent($id, $snRepo, $gradeRepo, $syRepo) {
        $year = session()->get('schoolYearSelected')+1900;
        $studentNumber = $snRepo -> find($id);
        $grades = $gradeRepo -> getAllSorted();
        $gradeSelected = $studentNumber->grade_id;
        $gradeSF = view('grade.selectField', ["name"=>"grade_id", "grades"=>$grades, "gradeSelected"=>$gradeSelected, "year"=>$year]);
        $schoolYears = $syRepo -> getAllSorted();
        $schoolYearSelected = $studentNumber->school_year_id;
        $schoolYearSF = view('schoolYear.selectField', ["schoolYears"=>$schoolYears, "schoolYearSelected"=>$schoolYearSelected, "name"=>"school_year_id"]);
        return view('studentNumber.editForStudent', ["studentNumber"=>$studentNumber, "gradeSF"=>$gradeSF, "schoolYearSF"=>$schoolYearSF]);
    }

    public function update($id, Request $request, StudentNumber $studentNumber) {
        $studentNumber = $studentNumber -> find($id);
        $this->validate($request, [
          'student_id' => 'required',
          'grade_id' => 'required',
          'school_year_id' => 'required',
          'number' => 'required|integer|between:1,99',
        ]);

        $studentNumber->student_id = $request->student_id;
        $studentNumber->grade_id = $request->grade_id;
        $studentNumber->school_year_id = $request->school_year_id;
        $studentNumber->number = $request->number;
        $studentNumber->confirmation_number = $request->confirmationNumber;
        $studentNumber -> save();
        return $studentNumber->id;
    }

    public function updateNumber(Request $request, StudentNumber $studentNumber) {
        $studentNumber = StudentNumber::find($request->id);
        $studentNumber->number = $request->number;
        $studentNumber -> save();
        return 0;
    }

    public function confirmNumbers($grade_id, $school_year_id, StudentNumber $studentNumber) {    // potwierdzenie numerów w klasie dla wskazanego roku szkolnego
        $studentNumber -> where('grade_id', '=', $grade_id) -> where('school_year_id', '=', $school_year_id)
            -> where('confirmation_number', '=', 0) -> update(['confirmation_number'=>1]);
        return 1;
    }

    public function destroy($id, StudentNumber $studentNumber) {
        $studentNumber = $studentNumber -> find($id);
        $studentNumber -> delete();
    }

    public function refreshSection(Request $request, StudentNumberRepository $snRepo, SchoolYearRepository $schoolYearRepo, GradeRepository $gradeRepo) {
        if($request->version == "forStudent")   return $this -> refreshTableForStudent($request->student_id, $snRepo, $schoolYearRepo);
        if($request->version == "forGrade")     return $this -> refreshTableForGrade($request->grade_id, $snRepo, $schoolYearRepo, $gradeRepo);
        return $request->version;
    }

    public function refreshTableForGrade($grade_id, $snRepo, $schoolYearRepo, $gradeRepo) {
        $schoolYears = $schoolYearRepo -> getAllSorted();
        $schoolYearSelected = session()->get('schoolYearSelected');
        $schoolYearSF = view('schoolYear.selectField', ["schoolYears"=>$schoolYears, "schoolYearSelected"=>$schoolYearSelected, "name"=>"school_year_id"]);
        if( $schoolYearSelected ) {
            $schoolYear = $schoolYearRepo -> find( $schoolYearSelected );
            $studentNumbers = $snRepo -> getGradeNumbersForSchoolYear($grade_id, $schoolYear->id);
        }
        else {
            $schoolYear = $schoolYearRepo -> find( $schoolYearRepo -> getSchoolYearIdForDate(date('Y-m-d')) );
            $schoolYearSF = view('schoolYear.selectField', ["schoolYears"=>$schoolYears, "schoolYearSelected"=>0, "name"=>"school_year_id" ]);
            $studentNumbers = $snRepo -> getGradeNumbers($grade_id);
        }
        $grade = $gradeRepo -> find($grade_id);
        $yearOfStudy = substr($schoolYear->date_end,0,4) - ($grade->year_of_beginning+1);
        if(substr($schoolYear->date_end,5,2)) $yearOfStudy++;
        $tableForGrade = view('studentNumber.tableForGrade', ["schoolYearSF"=>$schoolYearSF, "studentNumbers"=>$studentNumbers, "grade"=>$grade, "dateView"=>'2022-09-16']);
        $count = count($studentNumbers);
        return $tableForGrade;
        return view('studentNumber.sectionForGrade', ["grade"=>$grade, "yearOfStudy"=>$yearOfStudy, "tableForGrade"=>$tableForGrade, "count"=>$count]);
    }
/*
    public function refreshTableForStudent($student_id, $snRepo, $schoolYearRepo) {
        $studentNumbers = $snRepo -> getStudentNumbers($student_id);
        $year = 0;
        if( !empty(session()->get('schoolYearSelected')) ) {
            $schoolYear = $schoolYearRepo -> find(session()->get('schoolYearSelected'));
            $this->year = substr($schoolYear->date_end, 0, 4);
        }

        return view('studentNumber.tableForStudent', ["subTitle"=>"numery ucznia", "studentNumbers"=>$studentNumbers, "grade"=>0,
            "schoolYearSelectField"=>"", "student"=>$student_id, "yearOfStudy"=>$year]);
    }
*/
    public function refreshRow(Request $request, StudentNumber $studentNumber, SchoolYearRepository $schoolYearRepo) {
        $studentNumber = $studentNumber -> find($request->id);
        $year = 0;
        if( !empty(session()->get('schoolYearSelected')) ) {
            $schoolYear = $schoolYearRepo -> find(session()->get('schoolYearSelected'));
            $year = substr($schoolYear->date_end, 0, 4);
        }
        if($request->version == "forStudent")   return view('studentNumber.rowForStudent', ["sn"=>$studentNumber, "yearOfStudy"=>$year]);
        if($request->version == "forGrade")     return view('studentNumber.rowForGrade', ["sn"=>$studentNumber, "yearOfStudy"=>$year, "lp"=>$request->lp]);
        return $request->version;
    }
}