<?php
// ------------------------ (C) mgr inż. Bartłomiej Trojnar; 03.07.2023 ------------------------ //
namespace App\Http\Controllers;
use App\Repositories\GroupRepository;
use App\Models\Group;

use App\Models\GroupGrade;
use App\Models\GroupTeacher;
use App\Models\GroupStudent;
use App\Repositories\GradeRepository;
use App\Repositories\GroupGradeRepository;
use App\Repositories\GroupStudentRepository;
use App\Repositories\GroupTeacherRepository;
use App\Repositories\LessonRepository;
use App\Repositories\LessonPlanRepository;
use App\Repositories\SchoolYearRepository;
use App\Repositories\StudentGradeRepository;
use App\Repositories\SubjectRepository;
use App\Repositories\TeacherRepository;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function index(GroupRepository $groupRepo, GradeRepository $gradeRepo, SubjectRepository $subjectRepo, SchoolYearRepository $syRepo, TeacherRepository $teacherRepo) {
        $year = session()->get('schoolYearSelected');
        if( !empty($year) )  {
            $schoolYear = $syRepo -> find( $year );
            $year = substr($schoolYear->date_end, 0, 4);
        }
        $grades = $gradeRepo -> getFilteredAndSorted($year, 0);
        $gradeSelected = session()->get('gradeSelected');
        $gradeSF = view('grade.selectField', ["name"=>"grade_id", "grades"=>$grades, "gradeSelected"=>$gradeSelected, "year"=>$year]);

        $subjects = $subjectRepo -> getActualAndSorted();
        $subjectSelected = session()->get('subjectSelected');
        $subjectSF = view('subject.selectField', ["subjects"=>$subjects, "subjectSelected"=>$subjectSelected]);

        $levels = array('rozszerzony', 'podstawowy');
        $levelSelected = session()->get('levelSelected');
        $levelSF = view('layouts.levelSelectField', ["levels"=>$levels, "levelSelected"=>$levelSelected]);

        $teachers = $teacherRepo -> getFiltered( session()->get('schoolYearSelected') );
        $teacherSelected = session()->get('teacherSelected');
        $teacherSF = view('teacher.selectField', ["teachers"=>$teachers, "teacherSelected"=>$teacherSelected]);

        $schoolYears = $syRepo -> getAllSorted();
        $schoolYearSelected = session()->get('schoolYearSelected');
        $schoolYearSF = view('schoolYear.selectField', ["schoolYears"=>$schoolYears, "schoolYearSelected"=>$schoolYearSelected, "name"=>'schoolYear']);

        $start = session() -> get('dateView');
        if(!empty(session() -> get('dateEnd'))) $end = session() -> get('dateEnd'); else $end=$start;
        $groups = $groupRepo -> getFilteredAndSortedAndPaginate($gradeSelected, $subjectSelected, $levelSelected, $start, $end, $teacherSelected);
        $groupTable = view('group.table', ["groups"=>$groups, "links"=>true, "subTitle"=>"", "start"=>$start, "end"=>$end, "grade_id"=>$gradeSelected, "version"=>"forIndex",
            "gradeSF"=>$gradeSF, "subjectSF"=>$subjectSF, "levelSF"=>$levelSF, "teacherSF"=>$teacherSF, "schoolYearSF"=>$schoolYearSF]);

        return view('group.index', ["groupTable"=>$groupTable]);
    }

    public function orderBy($column) {
        if(session()->get('GroupOrderBy[0]') == $column)
            if(session()->get('GroupOrderBy[1]') == 'desc') session()->put('GroupOrderBy[1]', 'asc');
            else session()->put('GroupOrderBy[1]', 'desc');
        else {
            session()->put('GroupOrderBy[4]', session()->get('GroupOrderBy[2]'));
            session()->put('GroupOrderBy[2]', session()->get('GroupOrderBy[0]'));
            session()->put('GroupOrderBy[0]', $column);
            session()->put('GroupOrderBy[5]', session()->get('GroupOrderBy[3]'));
            session()->put('GroupOrderBy[3]', session()->get('GroupOrderBy[1]'));
            session()->put('GroupOrderBy[1]', 'asc');
        }
        return redirect( $_SERVER['HTTP_REFERER'] );
    }

    public function create(SubjectRepository $subjectRepo, SchoolYearRepository $syRepo, TeacherRepository $teacherRepo) {
        $subjects = $subjectRepo -> getActualAndSorted();
        $subjectSelected = session() -> get('subjectSelected');
        $subjectSF = view('subject.selectField', ["subjects"=>$subjects, "subjectSelected"=>$subjectSelected]);
        $levels = array('podstawowy', 'rozszerzony', 'dwujęzyczny', 'nieokreślony');
        $levelSelected = '';
        $levelSF = view('layouts.levelSelectField', ["levels"=>$levels, "levelSelected"=>$levelSelected]);
        $teacher = session()->get('teacherSelected');

        if( !empty(session() -> get('schoolYearSelected')) ) {
            $schoolYear = $syRepo -> find( session() -> get('schoolYearSelected') );
            $proposedDate = $schoolYear->date_end;
        }
        else $proposedDate = date('Y-m-d');
        $proposedDates = $syRepo -> getDatesOfSchoolYear($proposedDate);

        $history = $_SERVER['HTTP_REFERER'];
        if( strpos($history, "/nauczyciel/") && substr($history, -11) =="/showGroups" )
            $teacher = $teacherRepo -> find( session()->get('teacherSelected') );
        else $teacher = 0;

        return view('group.create', ["proposedDates"=>$proposedDates, "teacher"=>$teacher, "subjectSF"=>$subjectSF, "levelSF"=>$levelSF]);
    }

    public function copy($id, Group $group, SubjectRepository $subjectRepo, SchoolYearRepository $syRepo, TeacherRepository $teacherRepo) {
        $group = $group -> find($id);

        $subjects = $subjectRepo -> getActualAndSorted();
        $levels = array('podstawowy', 'rozszerzony', 'nieokreślony');

        $subjectSelected = $group->subject_id;
        $levelSelected = $group->level;

        if( !empty(session() -> get('schoolYearSelected')) ) {
            $schoolYear = $syRepo -> find( session() -> get('schoolYearSelected') );
            $proposedDate = $schoolYear->date_end;
        }
        else $proposedDate = date('Y-m-d');
        $proposedDates = $syRepo -> getDatesOfSchoolYear($proposedDate);

        $teacher = 0;

        return view('group.copy', ["proposedDates"=>$proposedDates, "group"=>$group])
            -> nest('subjectSelectField', 'subject.selectField', ["subjects"=>$subjects, "subjectSelected"=>$subjectSelected])
            -> nest('levelSelectField', 'layouts.levelSelectField', ["levels"=>$levels, "levelSelected"=>$levelSelected]);
    }

    public function store(Request $request) {
        if( $request->start > $request->end )  return redirect($request->history_view);
        $this -> validate($request, [   'subject_id' => 'required', 'start' => 'required|date', 'end' => 'required|date',
            'comments' => 'max:30', 'hours' => 'integer|max:9', ]);

        $group = new Group;
        $group->subject_id = $request->subject_id;
        $group->start = $request->start;
        $group->end = $request->end;
        $group->comments = $request->comments;
        $group->level = $request->level;
        $group->hours = $request->hours;
        $group -> save();

        return $group->id;

        // if($request->teacher_id)
            // return redirect( route('grupa_nauczyciele.automaticallyAddTeacher', ['group_id'=>$group->id, 'teacher_id'=>$request->teacher_id, 'start'=>$group->start, 'end'=>$group->end]) );
    }

    public function copyStore(Request $request) {
        if( $request->start > $request->end )  return redirect($request->history_view);
        $this -> validate($request, [
          'subject_id' => 'required',
          'start' => 'required|date',
          'end' => 'required|date',
          'comments' => 'max:30',
          'hours' => 'integer|max:9',
        ]);

        $group = new Group;
        $group->subject_id = $request->subject_id;
        $group->start = $request->start;
        $group->end = $request->end;
        $group->comments = $request->comments;
        $group->level = $request->level;
        $group->hours = $request->hours;
        $group -> save();

        // skopiowanie klas, nauczycieli i uczniów z oryginalnej grupy
        foreach($_POST as $key=>$value) {
            if(substr($key, 0, 5) == 'grade')       $this -> enterGrade($group->id, substr($key, 5));
            if(substr($key, 0, 7) == 'teacher')     $this -> enterTeacher($group, substr($key, 7));
            if(substr($key, 0, 7) == 'student')     $this -> enterStudent($group, substr($key, 7));
        }
        
        return redirect($request->history_view);
    }

    private function enterGrade($group_id, $grade_id) {
        $groupGrade = new GroupGrade;
        $groupGrade->group_id = $group_id;
        $groupGrade->grade_id = $grade_id;
        $groupGrade -> save();
    }

    private function enterTeacher($group, $teacher_id) {
        $groupTeacher = new GroupTeacher;
        $groupTeacher->group_id = $group->id;
        $groupTeacher->teacher_id = $teacher_id;
        $groupTeacher->start = $group->start;
        $groupTeacher->end = $group->end;
        $groupTeacher -> save();
    }

    private function enterStudent($group, $student_id) {
        $groupStudent = new GroupStudent;
        $groupStudent->group_id = $group->id;
        $groupStudent->student_id = $student_id;
        $groupStudent->start = $group->start;
        $groupStudent->end = $group->end;
        $groupStudent -> save();
    }

    public function change($id) {  session()->put('groupSelected', $id);  }

    public function show($id, GroupRepository $groupRepo, GradeRepository $gradeRepo, SchoolYearRepository $schoolYearRepo, GroupStudentRepository $groupStudentRepo, GroupGradeRepository $ggRepo, StudentGradeRepository $sgRepo, LessonPlanRepository $lessonPlanRepo, LessonRepository $lessonRepo, $view='') {
        session()->put('groupSelected', $id);
        if(empty( session()->get('groupView') ))  session()->put('groupView', 'info');
        if($view) session()->put('groupView', $view);
        $this->group = $groupRepo -> find($id);

        $gradeSelected = session()->get('gradeSelected');
        $subjectSelected = session()->get('subjectSelected');
        $groups = $groupRepo -> getFilteredAndSorted($gradeSelected, $subjectSelected);
        list($this->previous, $this->next) = $groupRepo -> nextAndPreviousRecordId($groups, $id);

        // pobranie informacji o roku szkolnym (aby wyświetlać rocznik klasy, jeżeli jest wybrany)
        $this->year = $schoolYearRepo -> getYear();

        switch( session() -> get('groupView') ) {
            case 'info':        return $this -> showInfo($gradeRepo);
            case 'uczniowie':   return $this -> showStudents($groupRepo, $schoolYearRepo, $groupStudentRepo, $ggRepo, $sgRepo);
            case 'planlekcji':  return $this -> showLessonPlan($lessonPlanRepo);
            case 'lekcje':      return $this -> showLessons($lessonRepo);
            default:
                printf('<p>Widok %s nieznany</p>', session() -> get('groupView'));
                session() -> put('groupView', 'info');
                printf('<p>Odśwież by zobaczyć domyślny widok</p>');
            break;
        }
    }

    private function showInfo($gradeRepo) {
        $css = "/group/info.css";
        $js = "/group/info.js";
        $grades = $gradeRepo -> getGroupGrades($this->group->id);
        $groupInfo = view('group.showInfo', ["group"=>$this->group, "grades"=>$grades, "year"=>$this->year, "dateView"=>session()->get('dateView')]);
        return view('group.show', ["group"=>$this->group, "year"=>$this->year, "css"=>$css, "js"=>$js, "previous"=>$this->previous, "next"=>$this->next, "subView"=>$groupInfo]);
    }

    private function showStudents($groupRepo, $schoolYearRepo, $groupStudentRepo, $groupGradeRepo, $studentGradeRepo) {
        $dateView = session()->get('dateView');
        $schoolYear = $schoolYearRepo -> getSchoolYearIdForDate($dateView);
        $groupStudents = $groupStudentRepo -> getGroupStudents($this->group->id);
        $listGroupStudents = view('groupStudent.listForGroup', ["groupStudents"=>$groupStudents, "schoolYear"=>$schoolYear, "dateView"=>$dateView, "group"=>$this->group, "year"=>$this->year]);
        $listGroupStudentsInOtherTime = view('groupStudent.listGroupStudentsInOtherTime', ["groupStudents"=>$groupStudents, "schoolYear"=>$schoolYear, "dateView"=>$dateView]);
        $groupGrades = $groupGradeRepo -> getGroupGrades($this->group->id);
        foreach($groupGrades as $gg) $grades[] = $gg->grade_id;
        $outsideGroupStudents = $studentGradeRepo -> getStudentsFromGrades($grades);
        //$outsideGroupStudents = $groupStudentRepo -> getOutsideGroupStudents($this->group, $dateView);

        $gradeSelected   = session()->get('gradeSelected');
        $subjectSelected = 0;
        $levelSelected   = 0;
        $teacherSelected = 0;
        $groups = $groupRepo -> getFilteredAndSorted($gradeSelected, $subjectSelected, $levelSelected, $dateView, $dateView, $teacherSelected);
        $groupSF = view('group.selectField', ["name"=>"selectedGroupID", "groups"=>$groups, "groupSelected"=>$this->group->id, "year"=>$this->year]);
        $listOutsideGroupStudents = view('groupStudent.listOutsideGroupStudents', ["outsideGroupStudents"=>$outsideGroupStudents, "schoolYear"=>$schoolYear, "dateView"=>$dateView, "groupSF"=>$groupSF, "year"=>$this->year]);

        $groupStudentTable = view('groupStudent.sectionListsForGroup', ["group"=>$this->group, "dateView"=>$dateView, "year"=>$this->year,
            "listGroupStudents"=>$listGroupStudents, "listGroupStudentsInOtherTime"=>$listGroupStudentsInOtherTime, "listOutsideGroupStudents"=>$listOutsideGroupStudents]);

        $css = "group/groupStudents.css";
        $js = "groupStudent/forGroup.js";
        return view('group.show', ["group"=>$this->group, "year"=>$this->year, "previous"=>$this->previous, "next"=>$this->next, "css"=>$css, "js"=>$js, "subView"=>$groupStudentTable]);
    }

    private function showLessonPlan($lessonPlanRepo) {
        $css = "";
        $js = "lessonPlan/forGroup.js";
        $groupLessons = $lessonPlanRepo -> getGroupLessons($this->group->id);
        $dateView = session()->get('dateView');
        $groupLessonPlan = view('lessonPlan.groupPlan', ["group"=>$this->group, "groupLessons"=>$groupLessons, "dateView"=>$dateView]);
        return view('group.show', ["group"=>$this->group, "year"=>$this->year, "previous"=>$this->previous, "next"=>$this->next, "css"=>$css, "js"=>$js, "subView"=>$groupLessonPlan]);
    }

    private function showLessons($lessonRepo) {
        $css = "";
        $js = "";
        $lessons = $lessonRepo -> getGroupLessons($this->group->id);
        $groupLessons = view('lesson.table', ["lessons"=>$lessons, "subTitle"=>"lekcje grupy"]);
        return view('group.show', ["group"=>$this->group, "year"=>$this->year, "previous"=>$this->previous, "next"=>$this->next, "css"=>$css, "js"=>$js, "subView"=>$groupLessons]);
    }

    public function edit(Request $request, Group $group, SubjectRepository $subjectRepo) {
        $group = $group -> find($request->id);
        $subjects = $subjectRepo -> getActualAndSorted();
        $subjectSF = view('subject.selectField', ["subjects"=>$subjects, "subjectSelected"=>$group->subject_id]);
        $levels = array('podstawowy', 'rozszerzony', 'dwujęzyczny', 'nieokreślony');
        $levelSF = view('layouts.levelSelectField', ["levels"=>$levels, "levelSelected"=>$group->level]);
        return view('group.editRow', ["version"=>$request->version, "group"=>$group, "subjectSF"=>$subjectSF, "levelSF"=>$levelSF, "lp"=>$request->lp]);
    }

    public function update($id, Request $request, Group $group) {
        if( $request->start > $request->end )  return redirect($request->history_view);
        $this -> validate($request, [
          'subject_id' => 'required',
          'start' => 'required|date',
          'end' => 'required|date',
          'comments' => 'max:30',
          'hours' => 'integer|max:9',
        ]);

        $group = $group -> find($id);
        $group->subject_id = $request->subject_id;
        $group->start = $request->start;
        $group->end = $request->end;
        $group->comments = $request->comments;
        $group->level = $request->level;
        $group->hours = $request->hours;
        $group -> save();
        
        return $group->id;
    }

    public function editComments(GroupRepository $groupRepo, SubjectRepository $subjectRepo) {
        $subjects = $subjectRepo -> getActualAndSorted();
        $subjectSelected = session()->get('subjectSelected');
        $subjectSelectField = view('subject.selectField', ["subjects"=>$subjects, "subjectSelected"=>$subjectSelected]);

        $levels = array('rozszerzony', 'podstawowy');
        $levelSelected = session()->get('levelSelected');
        $levelSelectField = view('layouts.levelSelectField', ["levels"=>$levels, "levelSelected"=>$levelSelected]);

        $groups = $groupRepo -> getFilteredAndSorted($subjectSelected, $levelSelected);
        $dateView = session() -> get('dateView');

        return view('group.editComments', ["groups"=>$groups, "dateView"=>$dateView, "subjectSelectField"=>$subjectSelectField, "levelSelectField"=>$levelSelectField]);
    }

    public function updateComments(Group $group) {
        foreach($_POST as $key=>$value) {
            if( substr($key, 0, 2) != 'id' ) continue;
            $id = $value;
            $group = Group::find($id);
            $group->comments = $_POST['comments'.$id];
            $group -> save();
        }
        return redirect( $_POST['history_view'] );
    }

    public function destroy($id, Group $group) {
        $group = $group -> find($id);
        $group -> delete();
        return 1;
    }

    public function hourSubtract(Request $request, GroupRepository $groupRepo) {
        $group = $groupRepo -> find($request->group_id);
        $group->hours = $group->hours-1;
        $group -> save();
        return $group->hours;
    }
    public function hourAdd(Request $request, GroupRepository $groupRepo) {
        $group = $groupRepo -> find($request->group_id);
        $group->hours = $group->hours+1;
        $group -> save();
        return $group->hours;
    }

    public function selectField(Request $request, GroupRepository $groupRepo) {
        $groups = $groupRepo -> getGroups($request->dateView, $request->dateView, $request->grade_id);
        return view('group.selectField', ["name"=>"groupChoice".$request->number, "groups"=>$groups, "groupSelected"=>0]);
    }

    public function extension(Request $request, Group $group, groupTeacherRepository $groupTeacherRepo, groupStudentRepository $groupStudentRepo) {
        // pobranie informacji o grupie
        $group = $group -> find($request->group_id);

        // przedłużenie czasu nauczania przez nauczycieli dla grupy (tych, którzy uczyli do dotychczasowej daty końcowej grupy)
        $groupTeachers = $groupTeacherRepo -> getGroupTeacherForGroup($request->group_id);
        foreach($groupTeachers as $groupTeacher) {
            if($groupTeacher->end == $group->end) {
                $groupTeacher->end = $request->dateGroupExtension;
                $groupTeacher -> save();
            }
        }

        // przedłużenie czasu przynależności uczniów do grupy (tych, którzy należeli do dotychczasowej daty końcowej grupy)
        $groupStudents = $groupStudentRepo -> getGroupStudents($request->group_id, $group->end);
        foreach($groupStudents as $groupStudent) {
            if($groupStudent->end == $group->end) {
                $groupStudent->end = $request->dateGroupExtension;
                $groupStudent -> save();
            }
        }

        $group->end = $request->dateGroupExtension;
        $group -> save();
        return 1;
    }

    public function refreshRow(Request $request, Group $group) {
        $group = $group -> find($request->id);
        $start = session() -> get('dateView');
        if(!empty(session() -> get('dateEnd'))) $end = session() -> get('dateEnd'); else $end=$start;
        $grade_id = session() -> get('gradeSelected');
        return view('group.row', ["group"=>$group, "version"=>$request->version, "lp"=>$request->lp, "start"=>$start, "end"=>$end, "grade_id"=>$grade_id]);
    }
}