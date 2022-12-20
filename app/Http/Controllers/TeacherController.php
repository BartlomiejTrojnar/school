<?php
// ------------------------ (C) mgr inż. Bartłomiej Trojnar; 20.12.2022 ------------------------ //
namespace App\Http\Controllers;
use App\Models\Teacher;
use App\Repositories\TeacherRepository;

use App\Models\TaughtSubject;
use App\Repositories\ClassroomRepository;
use App\Repositories\GradeRepository;
use App\Repositories\GroupRepository;
use App\Repositories\LessonPlanRepository;
use App\Repositories\SchoolRepository;
use App\Repositories\SchoolYearRepository;
use App\Repositories\SubjectRepository;

use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function index(TeacherRepository $teacherRepo, SchoolYearRepository $schoolYearRepo) {
        if( isset($_GET['page']) )  session()->put('TeacherPage', $_GET['page']);
        $teachers = $teacherRepo -> getAllSortedAndPaginate();
        $schoolYears = $schoolYearRepo -> getAllSorted();
        $schoolYearSF = view('schoolYear.selectField', ["schoolYears"=>$schoolYears, "schoolYearSelected"=>session() -> get('schoolYearSelected'), "name"=>"schoolYear_id" ]);
        $teacherTable = view('teacher.table', ["teachers"=>$teachers, "links"=>true, "subTitle"=>"", "schoolYearSF"=>$schoolYearSF]);
        return view('teacher.index', ["teacherTable"=>$teacherTable]);
    }

    public function orderBy($column) {
        if(session()->get('TeacherOrderBy[0]') == $column)
            if(session()->get('TeacherOrderBy[1]') == 'desc')   session()->put('TeacherOrderBy[1]', 'asc');
            else  session()->put('TeacherOrderBy[1]', 'desc');
        else {
            session()->put('TeacherOrderBy[4]', session()->get('TeacherOrderBy[2]'));
            session()->put('TeacherOrderBy[2]', session()->get('TeacherOrderBy[0]'));
            session()->put('TeacherOrderBy[0]', $column);
            session()->put('TeacherOrderBy[5]', session()->get('TeacherOrderBy[3]'));
            session()->put('TeacherOrderBy[3]', session()->get('TeacherOrderBy[1]'));
            session()->put('TeacherOrderBy[1]', 'asc');
        }

        return redirect( $_SERVER['HTTP_REFERER'] );
    }

    public function create(ClassroomRepository $classroomRepo, SchoolYearRepository $schoolYearRepo) {
        $classrooms = $classroomRepo -> getAllSorted();
        $schoolYears = $schoolYearRepo -> getAllSorted();
        $classroomSF = view('classroom.selectField', ["classrooms"=>$classrooms, "classroomSelected"=>0]);
        $firstYearSF = view('schoolYear.selectField', ["schoolYears"=>$schoolYears, "schoolYearSelected"=>0, "name"=>'first_year_id']);
        $lastYearSF = view('schoolYear.selectField', ["schoolYears"=>$schoolYears, "schoolYearSelected"=>0, "name"=>'last_year_id']);
        return view('teacher.create', ["classroomSF"=>$classroomSF, "firstYearSF"=>$firstYearSF, "lastYearSF"=>$lastYearSF]);
    }

    public function store(Request $request) {
        $this -> validate($request, [
          'first_name' => 'max:16',
          'last_name' => 'required|max:18',
          'family_name' => 'max:15',
          'short' => 'max:2',
          'degree' => 'max:10',
          'order' => 'required|integer|between:0,20',
        ]);

        $teacher = new Teacher;
        $teacher->first_name    = $request->first_name;
        $teacher->last_name     = $request->last_name;
        $teacher->family_name   = $request->family_name;
        $teacher->short  = $request->short;
        $teacher->degree = $request->degree;
        $teacher->classroom_id = $request->classroom_id;
        if($teacher->classroom_id==0) $teacher->classroom_id = NULL;
        $teacher->first_year_id = $request->first_year_id;
        $teacher->last_year_id  = $request->last_year_id;
        if($teacher->last_year_id == 0) $teacher->last_year_id = NULL;
        $teacher->order = $request->order;
        $teacher -> save();

        return $teacher->id;
    }

    public function change($id) { session()->put('teacherSelected', $id); }

    public function show($id, TeacherRepository $teacherRepo, SchoolYearRepository $syRepo, GradeRepository $gradeRepo, SubjectRepository $subjectRepo,
        GroupRepository $groupRepo, SchoolRepository $schoolRepo, LessonPlanRepository $lessonPlanRepo, $view='') {

        if(empty(session()->get('teacherView')))  session()->put('teacherView', 'info');
        if($view)  session()->put('teacherView', $view);
        $this->teacher = $teacherRepo -> find($id);
        session()->put('teacherSelected', $id);

        $teachers = $teacherRepo -> getAllSortedAndPaginate();
        list($this->previous, $this->next) = $teacherRepo -> nextAndPreviousRecordId($teachers, $id);

        switch( session()->get('teacherView') ) {
            case 'info':        return $this -> showInfo();
            case 'przedmioty':  return $this -> showSubjects();
            case 'grupy':       return $this -> showGroups($syRepo, $gradeRepo, $subjectRepo, $groupRepo);
            case 'klasy':       return $this -> showGrades($syRepo, $gradeRepo, $schoolRepo);
            case 'planlekcji':  return $this -> showLessonPlans($groupRepo, $lessonPlanRepo, $syRepo);
            default:
                printf('<p style="background: #bb0; color: #f00; font-size: x-large; text-align: center; border: 3px solid red; padding: 5px;">Widok %s nieznany</p>', session()->get('teacherView'));
        }
    }

    private function showInfo() {
        $teacherInfo = view('teacher.showInfo', ["teacher"=>$this->teacher]);
        return view('teacher.show', ["teacher"=>$this->teacher, "previous"=>$this->previous, "next"=>$this->next, "css"=>"", "js"=>"", "subView"=>$teacherInfo]);
    }

    private function showSubjects() {
        $taughtSubjects = $this->teacher -> subjects;
        $nonTaughtSubjects = TaughtSubject::nonTaughtSubjects($taughtSubjects);
        $teacherSubjects = view('teacher.showSubjects', ["teacher"=>$this->teacher, "taughtSubjects"=>$taughtSubjects, "nonTaughtSubjects"=>$nonTaughtSubjects]);
        $css = "taughtSubject.css";
        $js = "subject/forTeacher.js";
        return view('teacher.show', ["teacher"=>$this->teacher, "previous"=>$this->previous, "next"=>$this->next, "css"=>$css, "js"=>$js, "subView"=>$teacherSubjects]);
    }

    private function showGroups($syRepo, $gradeRepo, $subjectRepo, $groupRepo) {
        $schoolYear = session()->get('schoolYearSelected');
        if( !empty($schoolYear) )  {
            $schoolYear = $syRepo -> find( $schoolYear );
            $year = substr($schoolYear->date_end, 0, 4);
        }
        else $year = date('Y');
        $grades = $gradeRepo -> getFilteredAndSorted($year, 0);
        $gradeSelected = session()->get('gradeSelected');
        $gradeSF = view('grade.selectField', ["grades"=>$grades, "gradeSelected"=>$gradeSelected, "year"=>$year, "name"=>"grade_id"]);

        $subjectSelected = session()->get('subjectSelected');
        $subjects = $subjectRepo -> getActualAndSorted();
        $subjectSF = view('subject.selectField', ["subjects"=>$subjects, "subjectSelected"=>$subjectSelected]);

        $levelSelected = session()->get('levelSelected');
        $levels = array('rozszerzony', 'podstawowy');
        $levelSF = view('layouts.levelSelectField', ["levels"=>$levels, "levelSelected"=>$levelSelected]);

        $start = session() -> get('dateView');
        if(!empty(session() -> get('dateEnd'))) $end = session() -> get('dateEnd'); else $end=$start;
        $groups = $groupRepo -> getAllFilteredAndSorted($gradeSelected, $subjectSelected, $levelSelected, $start, $end, $this->teacher->id);
        $teacherGroups = view('group.table', ["groups"=>$groups, "subTitle"=>"grupy nauczyciela", "version"=>"forTeacher", "start"=>$start, "end"=>$end,
            "gradeSF"=>$gradeSF, "subjectSF"=>$subjectSF, "levelSF"=>$levelSF, "teacherSF"=>"", "schoolYearSF"=>"", "grade_id"=>0]);
        $js = "group/operations.js";
        return view('teacher.show', ["teacher"=>$this->teacher, "previous"=>$this->previous, "next"=>$this->next, "css"=>"", "js"=>$js, "subView"=>$teacherGroups]);
    }

    private function showGrades($syRepo, $gradeRepo, $schoolRepo) {
        $schoolYear = session()->get('schoolYearSelected');
        if( !empty($schoolYear) )  {
            $schoolYear = $syRepo -> find( $schoolYear );
            $year = substr($schoolYear->date_end, 0, 4);
        }
        else $year = date('Y');
        $grades = $gradeRepo -> getTeacherGrades($this->teacher->id, $year, 0);

        $schools = $schoolRepo -> getAllSorted();
        $schoolSelected = session()->get('schoolSelected');
        $schoolSF = view('school.selectField', ["schools"=>$schools, "schoolSelected"=>$schoolSelected]);

        $schoolYears = $syRepo -> getAllSorted();
        $schoolYearSelected = session()->get('schoolYearSelected');
        $schoolYearSF = view('schoolYear.selectField', ["schoolYears"=>$schoolYears, "schoolYearSelected"=>$schoolYearSelected, "name"=>"school_year_id"]);
        $teacherGrades = view('grade.tableForTeacher', ["schoolYearSF"=>$schoolYearSF, "schoolSF"=>$schoolSF, "grades"=>$grades, "year"=>$year]);
        return view('teacher.show', ["teacher"=>$this->teacher, "previous"=>$this->previous, "next"=>$this->next, "css"=>"", "js"=>"", "subView"=>$teacherGrades]);
    }

    private function showLessonPlans($groupRepo, $lessonPlanRepo, $schoolYearRepo) {
        $dateView = session() -> get('dateView');   if($dateView=="") $dateView = date('Y-m-d');
        $groups = $groupRepo -> getTeacherGroupsForDate($this->teacher->id, $dateView);
        $lessons = $lessonPlanRepo -> getTeacherLessons($this->teacher->id);
        $year = substr($dateView, 0, 4);
        if( substr($dateView, 5, 2)>=8 ) $year++;
        $schoolYearEnds = $schoolYearRepo -> getSchoolYearEnds($year-1, $year);     // znalezienie dat końcowych roku szkolnego w czasie istnienia klasy
        $teacherLessonPlan = view('lessonPlan.teacherPlan', ["lessons"=>$lessons, "groups"=>$groups, "dateView"=>$dateView, "schoolYearEnds"=>$schoolYearEnds, "year"=>$year]);
        $js = "lessonPlan/forTeacher.js";
        return view('teacher.show', ["teacher"=>$this->teacher, "previous"=>$this->previous, "next"=>$this->next, "css"=>"", "js"=>$js, "subView"=>$teacherLessonPlan]);
    }

    public function edit(Request $request, Request $request, Teacher $teacher, ClassroomRepository $classroomRepo, SchoolYearRepository $schoolYearRepo) {
        $teacher = $teacher -> find($request->id);
        $classrooms = $classroomRepo->getAllSorted();
        $schoolYears = $schoolYearRepo->getAllSorted();
        $classroomSF = view('classroom.selectField', ["classrooms"=>$classrooms, "classroomSelected"=>$teacher->classroom_id]);
        $firstYearSF = view('schoolYear.selectField', ["schoolYears"=>$schoolYears, "schoolYearSelected"=>$teacher->first_year_id, "name"=>'first_year_id']);
        $lastYearSF = view('schoolYear.selectField', ["schoolYears"=>$schoolYears, "schoolYearSelected"=>$teacher->last_year_id, "name"=>'last_year_id']);
        return view('teacher.edit', ["teacher"=>$teacher, "lp"=>$request->lp, "classroomSF"=>$classroomSF, "firstYearSF"=>$firstYearSF, "lastYearSF"=>$lastYearSF]);
    }

    public function update($id, Request $request, Teacher $teacher) {
        $teacher = $teacher -> find($id);
        $this->validate($request, [
          'first_name' => 'max:16',
          'last_name' => 'required|max:18',
          'family_name' => 'max:15',
          'short' => 'max:2',
          'degree' => 'max:10',
          'order' => 'required|integer|between:0,20',
        ]);

        $teacher->first_name    = $request->first_name;
        $teacher->last_name     = $request->last_name;
        $teacher->family_name   = $request->family_name;
        $teacher->short         = $request->short;
        $teacher->degree        = $request->degree;
        $teacher->classroom_id = $request->classroom_id;
        if($teacher->classroom_id==0) $teacher->classroom_id = NULL;
        $teacher->first_year_id = $request->first_year_id;
        if($teacher->first_year_id==0) $teacher->first_year_id = NULL;
        $teacher->last_year_id = $request->last_year_id;
        if($teacher->last_year_id==0) $teacher->last_year_id = NULL;
        $teacher->order = $request->order;
        $teacher->save();

        return $teacher->id;
    }

    public function destroy($id, Teacher $teacher) {
        $teacher = $teacher -> find($id);
        $teacher -> delete();
        return 1;
    }

    public function printOrder(TeacherRepository $teacherRepo) {
        $teachers = $teacherRepo -> getAll();
        return view('teacher.printOrder', ["teachers"=>$teachers]);
    }

    public function setPrintOrder(Request $request, Teacher $teacher) {
        $teacher = $teacher -> find($request->teacher_id);
        $teacher->order = $request->order;
        $teacher->save();
        return $teacher->id;
    }

    public function refreshRow(Request $request, TeacherRepository $teacherRepo) {
        $this->teacher = $teacherRepo -> find($request->id);
        return view('teacher.row', ["teacher"=>$this->teacher, "lp"=>$request->lp]);
    }
}