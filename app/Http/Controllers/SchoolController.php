<?php
// ------------------------ (C) mgr inż. Bartłomiej Trojnar; 27.07.2021 ------------------------ //
namespace App\Http\Controllers;
use App\Models\School;
use App\Repositories\SchoolRepository;

use App\Repositories\GradeRepository;
use App\Repositories\SchoolYearRepository;
use App\Repositories\StudentRepository;
use Illuminate\Http\Request;

class SchoolController extends Controller
{
    public function create() {  return view('school.create');  }

    public function store(Request $request) {
        $this -> validate($request, [ 'name' => 'required|max:45', 'id_OKE' => 'max:12', ]);
        $school = new School;
        $school->name = $request->name;
        $school->id_OKE = $request->id_OKE;
        $school -> save();
        return $school->id;
    }

    public function edit($id, SchoolRepository $schoolRepo) {
        $school = $schoolRepo -> find($id);
        return view('school.edit', ["school"=>$school]);
    }

    public function update($id, Request $request, School $school) {
        $school = School::find($id);
        $this -> validate($request, [ 'name' => 'required|max:45', 'id_OKE' => 'max:12', ]);
        $school->name = $request->name;
        $school->id_OKE = $request->id_OKE;
        $school -> save();
        return $school->id;
    }

    public function destroy($id, School $school) {
        $school = School::find($id);
        $school -> delete();
        return 1;
    }

    public function refreshRow(Request $request, SchoolRepository $schoolRepo) {
        $this->school = $schoolRepo -> find($request->id);
        return view('school.row', ["school"=>$this->school, "lp"=>$request->lp]);
    }

    public function index(SchoolRepository $schoolRepo) {
        $schools = $schoolRepo -> getAllSorted();
        return view('school.index', ["schools"=>$schools]);
    }

    public function orderBy($column) {
        if(session()->get('SchoolOrderBy[0]') == $column)
            if(session()->get('SchoolOrderBy[1]') == 'desc')  session()->put('SchoolOrderBy[1]', 'asc');
            else  session()->put('SchoolOrderBy[1]', 'desc');
        else {
            session()->put('SchoolOrderBy[2]', session()->get('SchoolOrderBy[0]'));
            session()->put('SchoolOrderBy[0]', $column);
            session()->put('SchoolOrderBy[3]', session()->get('SchoolOrderBy[1]'));
            session()->put('SchoolOrderBy[1]', 'asc');
        }
        return redirect( route('szkola.index') );
    }
/*
    public function change($id) {  session()->put('schoolSelected', $id);  }

    public function show($id, SchoolRepository $schoolRepo, StudentRepository $studentRepo, GradeRepository $gradeRepo, SchoolYearRepository $schoolYearRepo, $view='') {
        session()->put('schoolSelected', $id);
        if(empty(session()->get('schoolView')))  session()->put('schoolView', 'showInfo');
        if(!empty($view))  session()->put('schoolView', $view);
        $this->school = $schoolRepo -> find($id);

        $schools = $schoolRepo -> getAllSorted();
        list($this->previous, $this->next) = $schoolRepo -> nextAndPreviousRecordId($schools, $id);

        switch( session()->get('schoolView') ) {
            case 'showInfo':        return $this -> showInfo();
            case 'showStudents':    return $this -> showStudents($studentRepo, $gradeRepo, $schoolYearRepo);
            case 'showGrades':      return $this -> showGrades($gradeRepo, $schoolYearRepo);
            default:
                printf('<p style="background: #bb0; color: #f00; font-size: x-large; text-align: center; border: 3px solid red; padding: 5px;">Widok %s nieznany</p>', session()->get('schoolView'));
        }
    }

    private function showInfo() {
        return view('school.show', ["school"=>$this->school, "previous"=>$this->previous, "next"=>$this->next, "css"=>"", "js"=>""])
            -> nest('subView', 'school.showInfo', ["school"=>$this->school]);
    }

    private function showStudents($studentRepo, $gradeRepo, $schoolYearRepo) {
        $students = $studentRepo -> getStudentsFromSchool($this->school->id);
        $grades = $gradeRepo -> getAllSorted();
        $gradeSelectField = view('grade.selectField', ["name"=>"grade_id", "grades"=>$grades, "gradeSelected"=>session() -> get('gradeSelected') ]);
        $schoolYears = $schoolYearRepo -> getAllSorted();
        $schoolYearSelectField = view('schoolYear.selectField', ["schoolYears"=>$schoolYears, "schoolYearSelected"=>session() -> get('schoolYearSelected'), "name"=>"schoolYear_id" ]);

        // przygotowanie widoków
        $studentsTable = view('student.tableForSchool', ["school"=>$this->school, "students"=>$students, "gradeSelectField"=>$gradeSelectField, "schoolYearSelectField"=>$schoolYearSelectField, "showDateView"=>0]);
        return view('school.show', ["school"=>$this->school, "previous"=>$this->previous, "next"=>$this->next, "subView"=>$studentsTable, "css"=>"", "js"=>""]);
    }

    private function showGrades($gradeRepo, $schoolYearRepo) {
        $schoolYearSelected = session()->get('schoolYearSelected');
        $schoolYears = $schoolYearRepo -> getAllSorted();
        $schoolYearSelectField = view('schoolYear.selectField', ["schoolYears"=>$schoolYears, "schoolYearSelected"=>$schoolYearSelected, "name"=>"school_year_id"]);
        if($schoolYearSelected) {
            $schoolYear = $schoolYearRepo -> find($schoolYearSelected);
            $year = substr($schoolYear->date_end, 0, 4);
        }
        else $year=0;
        $grades = $gradeRepo -> getFilteredAndSortedAndPaginate($year, $this->school->id);
        $gradeTable = view('grade.tableForSchool', ["grades"=>$grades, "schoolYearSelectField"=>$schoolYearSelectField, "year"=>$year]);
        $css = "";
        $js = "school/grade.js";
        return view('school.show', ["school"=>$this->school, "previous"=>$this->previous, "next"=>$this->next, "subView"=>$gradeTable, "css"=>$css, "js"=>$js]);
    }
    */
}