<?php
// ------------------------ (C) mgr inż. Bartłomiej Trojnar; 01.12.2021 ------------------------ //
namespace App\Http\Controllers;
use App\Models\Declaration;
use App\Repositories\DeclarationRepository;

use App\Repositories\ExamRepository;
use App\Repositories\GradeRepository;
use App\Repositories\StudentRepository;
use App\Repositories\StudentGradeRepository;
use App\Repositories\SessionRepository;
use Illuminate\Http\Request;

class DeclarationController extends Controller
{
    public function index(DeclarationRepository $declarationRepo, SessionRepository $sessionRepo, GradeRepository $gradeRepo, StudentRepository $studentRepo, StudentGradeRepository $studentGradeRepo) {
        $sessionSelected = session()->get('sessionSelected');
        $gradeSelected = session()->get('gradeSelected');
        $studentSelected = session()->get('studentSelected');

        $grades = $gradeRepo -> getAllSorted();
        $gradeSelectField = view('grade.selectField', ["grades"=>$grades, "gradeSelected"=>$gradeSelected, "name"=>"grade_id"]);
        if($gradeSelected) {
            $grades2[0] = $gradeSelected;
            $studentGrades = $studentGradeRepo -> getStudentsFromGrades($grades2, 0);
            foreach($studentGrades as $studentGrade)    $students[] = $studentGrade->student;
        }
        else  $students = $studentRepo -> getAllSorted();

        $studentSelectField = view('student.selectField', ["students"=>$students, "studentSelected"=>$studentSelected]);
        $sessions = $sessionRepo -> getAllSorted();
        $sessionSelectField = view('session.selectField', ["sessions"=>$sessions, "sessionSelected"=>$sessionSelected]);

        $declarations = $declarationRepo -> getFilteredAndSortedAndPaginate($sessionSelected, $gradeSelected, $studentSelected);
        return view('declaration.index', ["declarations"=>$declarations, "gradeSelectField"=>$gradeSelectField, "studentSelectField"=>$studentSelectField, "sessionSelectField"=>$sessionSelectField]);
    }

    public function orderBy($column) {
        if(session()->get('DeclarationOrderBy[0]') == $column)
            if(session()->get('DeclarationOrderBy[1]') == 'desc') session()->put('DeclarationOrderBy[1]', 'asc');
            else session()->put('DeclarationOrderBy[1]', 'desc');
        else {
            session()->put('DeclarationOrderBy[4]', session()->get('DeclarationOrderBy[2]'));
            session()->put('DeclarationOrderBy[2]', session()->get('DeclarationOrderBy[0]'));
            session()->put('DeclarationOrderBy[0]', $column);
            session()->put('DeclarationOrderBy[5]', session()->get('DeclarationOrderBy[3]'));
            session()->put('DeclarationOrderBy[3]', session()->get('DeclarationOrderBy[1]'));
            session()->put('DeclarationOrderBy[1]', 'asc');
        }
        return redirect( $_SERVER['HTTP_REFERER'] );
    }

    public function create(Request $request, SessionRepository $sessionRepo, GradeRepository $gradeRepo, StudentGradeRepository $studentGradeRepo) {
        $sessions = $sessionRepo -> getAllSorted();
        $session = view('session.selectField', ["sessions"=>$sessions, "sessionSelected"=>session()->get('sessionSelected')]);
        if($request->version == "forSession")   $session = session()->get('sessionSelected');

        if($request->version=="forGrade") {
            $grades = $gradeRepo -> getFilteredAndSorted(date('Y'));
            $gradeSelected = session()->get('gradeSelected');
            $gradeSelectField = view('grade.selectField', ["name"=>"grade_id", "grades"=>$grades, "gradeSelected"=>$gradeSelected]);
            return view('declaration.createForGrade', ["sessionSelectField"=>$session, "gradeSelectField"=>$gradeSelectField]);
        }

        if($request->version == "forStudent") {
            $student = session()->get('studentSelected');
            return view('declaration.createForStudent', ["version"=>$request->version, "session"=>$session, "student"=>$student]);
        }

        $gradeSelected = session()->get('gradeSelected');
        if($gradeSelected)  $grades[0] = $gradeSelected;
        else {
            $allGrades = $gradeRepo -> getFilteredAndSorted(date('Y'));
            $grades = [];
            foreach($allGrades as $grade)   $grades[] = $grade->id;
        }
        $studentGrades = $studentGradeRepo -> getStudentsFromGrades($grades, 0);
        $students = [];
        foreach($studentGrades as $studentGrade) $students[] = $studentGrade->student;
        $student = view('student.selectField', ["students"=>$students, "studentSelected"=>session()->get('studentSelected')]);

        return view('declaration.create', ["version"=>$request->version, "session"=>$session, "student"=>$student]);
    }

    public function store(Request $request) {
        $this->validate($request, [
          'student_id' => 'required',
          'session_id' => 'required',
          'application_number' => 'required|integer|min:1|max:10',
          'student_code' => 'max:3',
        ]);

        $declaration = new Declaration;
        $declaration->student_id = $request->student_id;
        $declaration->session_id = $request->session_id;
        $declaration->application_number = $request->application_number;
        $declaration->student_code = $request->student_code;
        $declaration -> save();
        return $declaration->id;
    }

    public function storeForGrade(Request $request) {
        foreach($_POST as $key=>$value) {
            if(substr($key, 0, 7) != 'student') continue;
            $declaration = new Declaration;
            $declaration->student_id = $value;
            $declaration->session_id = $request->session_id;
            $declaration->application_number = $request->application_number;
            echo $value.'<br/>';
            $declaration -> save();
        }
        return redirect($request->history_view);
    }

    public function change($id) {  session()->put('declarationSelected', $id);  }

    public function show($id, DeclarationRepository $declarationRepo, ExamRepository $examRepo, $view='') {
        if(empty(session()->get('declarationView')))  session()->put('declarationView', 'showInfo');
        if($view)  session()->put('declarationView', $view);
        $declaration = $declarationRepo -> find($id);
        $declarations = $declarationRepo -> getFilteredAndSorted(session()->get('sessionSelected'), session()->get('gradeSelected'), session()->get('studentSelected'));
        list($this->previous, $this->next) = $declarationRepo -> nextAndPreviousRecordId($declarations, $id);

        $exams = $examRepo -> getFilteredAndSorted($declaration->id, 0, 0, 0, '');
        $countExams = count($exams);
        $examsTable = view('exam.table', ["exams"=>$exams, "countExams"=>$countExams, "version"=>"forDeclaration"]);

        $css = "declaration/exams.css";
        $js = "exam/operations.js";

        return view('declaration.show', ["declaration"=>$declaration, "previous"=>$this->previous, "next"=>$this->next, "css"=>$css, "js"=>$js, "examsTable"=>$examsTable]);
    }

    public function edit(Request $request, Declaration $declaration, GradeRepository $gradeRepo, SessionRepository $sessionRepo, StudentGradeRepository $studentGradeRepo, StudentRepository $studentRepo) {
        $declaration = $declaration -> find($request->id);
        $sessions = $sessionRepo -> getAllSorted();
        $session = view('session.selectField', ["sessions"=>$sessions, "sessionSelected"=>$declaration->session_id]);

        if($request->version == "forStudent")
            return view('declaration.editForStudent', ["declaration"=>$declaration, "version"=>$request->version, "session"=>$session]);

        $gradeSelected = session()->get('gradeSelected');
        if($gradeSelected)  $grades[0] = $gradeSelected;
        else {
            $allGrades = $gradeRepo -> getFilteredAndSorted(date('Y'));
            $grades = [];
            foreach($allGrades as $grade)   $grades[] = $grade->id;
        }
        $studentGrades = $studentGradeRepo -> getStudentsFromGrades($grades, 0);
        $students = [];
        foreach($studentGrades as $studentGrade) $students[] = $studentGrade->student;
        $student = view('student.selectField', ["students"=>$students, "studentSelected"=>$declaration->student_id]);

        return view('declaration.edit', ["declaration"=>$declaration, "version"=>$request->version, "session"=>$session, "student"=>$student]);

        if($request->version == "forGrade")     return $this -> editForGrade($request->id, $declaration, $sessionRepo, $studentGradeRepo);
        return $request->version;
    }

    public function update($id, Request $request, Declaration $declaration) {
        $declaration = $declaration -> find($id);
        $this->validate($request, [
          'student_id' => 'required',
          'session_id' => 'required',
          'application_number' => 'required|integer|min:1|max:10',
          'student_code' => 'max:3',
        ]);

        $declaration->student_id = $request->student_id;
        $declaration->session_id = $request->session_id;
        $declaration->application_number = $request->application_number;
        $declaration->student_code = $request->student_code;
        $declaration -> save();

        return $declaration->id;
    }

    public function destroy($id, Declaration $declaration) {
        $declaration = $declaration -> find($id);
        $declaration -> delete();
        return 1;
    }

    public function getListStudentWithoutDeclarationForGrade(Request $request, GradeRepository $gradeRepo, StudentGradeRepository $studentGradeRepo) {
        $grades[0] = $request->grade_id;
        $grade = $gradeRepo -> find($request->grade_id);
        $date = $grade->year_of_graduation."-04-01";
        $studentGrades = $studentGradeRepo -> getStudentsFromGrades($grades, $date, $date);
        $i=0;
        $session_id = session()->get('sessionSelected');
        foreach($studentGrades as $studentGrade) {
            $students[] = $studentGrade->student;
            $students[$i]['countDeclarations'] = 0;
            foreach($studentGrade->student->declarations as $declaration)
                if($declaration->session_id==$session_id)   $students[$i]['countDeclarations'] = 1;
            $i++;
        }
        return view('declaration.listStudentWithoutDeclarationForGrade', ["students"=>$students]);
    }

    public function refreshRow(Request $request, Declaration $declaration) {
        $declaration = $declaration -> find($request->id);
        return view('declaration.row', ["declaration"=>$declaration, "version"=>$request->version, "lp"=>$request->lp]);
    }

    public function refreshForStudent(Request $request, Declaration $declaration) {
        $declaration = $declaration -> find($request->id);
        return view('declaration.divForStudent', ["declaration"=>$declaration]);
    }
}