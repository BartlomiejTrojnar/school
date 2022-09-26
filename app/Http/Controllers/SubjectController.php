<?php
// ------------------------ (C) mgr inż. Bartłomiej Trojnar; 26.09.2022 ------------------------ //
namespace App\Http\Controllers;
use App\Models\Subject;
use App\Repositories\SubjectRepository;

use App\Models\TaughtSubject;
use App\Repositories\ExamDescriptionRepository;
use App\Repositories\GradeRepository;
use App\Repositories\GroupRepository;
use App\Repositories\SessionRepository;
use App\Repositories\SchoolYearRepository;
use App\Repositories\TeacherRepository;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index(SubjectRepository $subjectRepo) {
        $subjects = $subjectRepo -> getAllSortedAndPaginate();
        $js = "subject/index.js";
        return view('subject.index', ["js"=>$js]) -> nest('subjectTable', 'subject.table', ["subjects"=>$subjects, "subTitle"=>"", "links"=>true]);
    }

    public function orderBy($column) {
        if(session()->get('SubjectOrderBy[0]') == $column)
            if(session()->get('SubjectOrderBy[1]') == 'desc')  session()->put('SubjectOrderBy[1]', 'asc');
            else  session()->put('SubjectOrderBy[1]', 'desc');
        else {
            session()->put('SubjectOrderBy[4]', session()->get('SubjectOrderBy[2]'));
            session()->put('SubjectOrderBy[2]', session()->get('SubjectOrderBy[0]'));
            session()->put('SubjectOrderBy[0]', $column);
            session()->put('SubjectOrderBy[5]', session()->get('SubjectOrderBy[3]'));
            session()->put('SubjectOrderBy[3]', session()->get('SubjectOrderBy[1]'));
            session()->put('SubjectOrderBy[1]', 'asc');
        }

        return redirect( $_SERVER['HTTP_REFERER'] );
    }

    public function create() {  return view('subject.create');  }

    public function store(Request $request) {
        $this->validate($request, [
          'name' => 'required|max:60',
          'short_name' => 'max:15',
          'order_in_the_sheet' => 'integer|between:1,99',
        ]);

        $subject = new Subject;
        $subject->name = $request->name;
        $subject->short_name = $request->short_name;
        $subject->actual = $request->actual;
        if($request->actual) $subject->actual=1;    else $subject->actual=0;
        $subject->order_in_the_sheet = $request->order_in_the_sheet;
        if( empty($request->order_in_the_sheet) ) $subject->order_in_the_sheet = NULL;
        $subject->expanded = $request->expanded;
        if($request->expanded) $subject->expanded=1;    else $subject->expanded=0;
        $subject->save();

        return $subject->id;
    }

    public function change($id) {  session()->put('subjectSelected', $id);   }

    public function show($id, SubjectRepository $subjectRepo, SchoolYearRepository $syR, GradeRepository $gradeRepo, TeacherRepository $tR, GroupRepository $groupRepo, SessionRepository $sessionRepo, ExamDescriptionRepository $edR, $view='') {
        if(empty(session()->get('subjectView')) || session()->get('subjectView')=='change')  session()->put('subjectView', 'info');
        if($view)  session()->put('subjectView', $view);
        session() -> put('subjectSelected', $id);
        $this->subject = $subjectRepo -> find($id);

        $subjects = $subjectRepo -> getAllSortedAndPaginate();
        list($this->previous, $this->next) = $subjectRepo -> nextAndPreviousRecordId($subjects, $id);

        switch(session()->get('subjectView')) {
            case 'info':            return $this -> showInfo($this->subject);
            case 'nauczyciele':     return $this -> showTeachers($this->subject, $syR);
            case 'grupy':           return $this -> showGroups($syR, $gradeRepo, $tR, $groupRepo);
            case 'opisy-egzaminow': return $this -> showExamDescriptions($this->subject, $sessionRepo, $edR);
            case 'podreczniki':     return $this -> showTextbooks($this->subject);
            default:
                printf('<p style="background: #bb0; color: #f00; font-size: x-large; text-align: center; border: 3px solid red; padding: 5px;">Widok %s nieznany</p>', session()->get('subjectView'));
        }
    }

    private function showInfo($subject) {
        return view('subject.show', ["subject"=>$subject, "previous"=>$this->previous, "next"=>$this->next, "js"=>""])
            -> nest('subView', 'subject.showInfo', ["subject"=>$subject]);
    }

    private function showTeachers($subject, $schoolYearRepo) {
        $schoolYears = $schoolYearRepo->getAllSorted();
        $subjectTeachers = $subject -> teachers;
        $js = "teacher/forSubject.js";
        //$schoolYear_id = session() -> get('schoolYearSelected');
        //if(!$schoolYear_id) $schoolYear_id=0;
        //else for($i=0; $i<sizeof($subjectTeachers); $i++) {
        //    if($subjectTeachers[$i]->teacher->first_year_id > $schoolYear_id ||
        //      ($subjectTeachers[$i]->teacher->last_year_id!= "" && $subjectTeachers[$i]->teacher->last_year_id < $schoolYear_id))
        //        unset($subjectTeachers[$i]);
        //}
        $unlearningTeachers = TaughtSubject::unlearningTeachers($subjectTeachers);
        $schoolYearSF = view('schoolYear.selectField', ["schoolYears"=>$schoolYears, "schoolYearSelected"=>session()->get('schoolYearSelected'), "name"=>"schoolYear_id" ]);
        $subjectTeachersView = view('teacher.viewForSubject', ["subject"=>$subject, "subjectTeachers"=>$subjectTeachers, "unlearningTeachers"=>$unlearningTeachers, "schoolYearSF"=>$schoolYearSF]);
        return view('subject.show', ["subject"=>$subject, "js"=>$js, "previous"=>$this->previous, "next"=>$this->next, "subView"=>$subjectTeachersView]);
    }

    private function showGroups($syRepo, $gradeRepo, $teacherRepo, $groupRepo) {
        $gradeSelected = session()->get('gradeSelected');
        $levelSelected = session()->get('levelSelected');
        $start = session() -> get('dateView');
        if(!empty(session() -> get('dateEnd'))) $end = session() -> get('dateEnd'); else $end=$start;
        $groups = $groupRepo -> getFilteredAndSorted($gradeSelected, $this->subject->id, $levelSelected, $start, $end);
        $schoolYear = session()->get('schoolYearSelected');
        if( !empty($schoolYear) )  $schoolYear = $syRepo -> find( $schoolYear );
        $grades = $gradeRepo -> getFilteredAndSorted(substr($schoolYear->date_end, 0, 4), 0);
        $gradeSF = view('grade.selectField', ["name"=>"grade_id", "grades"=>$grades, "gradeSelected"=>$gradeSelected, "year"=>substr($schoolYear->date_end, 0, 4)]);
        $levels = array('rozszerzony', 'podstawowy');
        $levelSF = view('layouts.levelSelectField', ["levels"=>$levels, "levelSelected"=>$levelSelected]);
        $teachers = $teacherRepo -> getAll();
        $teacherSelected = session()->get('teacherSelected');
        $teacherSF = view('teacher.selectField', ["teachers"=>$teachers, "teacherSelected"=>$teacherSelected]);
        $groupTable = view('group.table', ["subTitle"=>"grupy przedmiotu", "groups"=>$groups, "link"=>true, "start"=>$start, "end"=>$end,
            "grade_id"=>$gradeSelected, "gradeSF"=>$gradeSF, "subjectSF"=>"",
            "levelSF"=>$levelSF, "teacherSF"=>$teacherSF, "schoolYearSF"=>"", "version"=>"forSubject"]);
        return view('subject.show', ["subject"=>$this->subject, "previous"=>$this->previous, "next"=>$this->next, "subView"=>$groupTable, "js"=>""]);
    }

    private function showExamDescriptions($subject, $sessionRepo, $examDescriptionRepo) {
        $sessions = $sessionRepo -> getAllSorted();
        $sessionSelected = session()->get('sessionSelected');
        $sessionSF = view('session.selectField', ["sessions"=>$sessions, "sessionSelected"=>$sessionSelected]);

        $examTypes = array('pisemny', 'ustny');
        $examTypeSelected = session()->get('examTypeSelected');;
        $examTypeSF = view('examDescription.examTypeSelectField', ["examTypes"=>$examTypes, "examTypeSelected"=>$examTypeSelected]);

        $levels = array('rozszerzony', 'podstawowy', 'nieustalony');
        $levelSelected = session()->get('levelSelected');;
        $levelSF = view('layouts.levelSelectField', ["levels"=>$levels, "levelSelected"=>$levelSelected]);

        $examDescriptions = $examDescriptionRepo -> getFilteredAndSorted($subject->id, $sessionSelected, $examTypeSelected, $levelSelected);
        $countDesc = count($examDescriptions);
        $examDescriptionsTable = view('examDescription.tableForSubject', ["countDesc"=>$countDesc, "sessionSF"=>$sessionSF, "examTypeSF"=>$examTypeSF, "levelSF"=>$levelSF, "examDescriptions"=>$examDescriptions]);

        return view('subject.show', ["subject"=>$subject, "previous"=>$this->previous, "next"=>$this->next, "subView"=>$examDescriptionsTable]);
    }

    private function showTextbooks($subject) {
        $subTitle = "podręczniki dla przedmiotu";
        $textbooks = $subject -> textbooks;
        $textbookTable = view('textbook.tableForSubject', ["subTitle"=>$subTitle, "textbooks"=>$textbooks]);
        return view('subject.show', ["subject"=>$subject, "previous"=>$this->previous, "next"=>$this->next, "subView"=>$textbookTable, "js"=>""]);
    }

    public function edit(Request $request, Subject $subject) {
        $subject = $subject -> find($request->id);
        return view('subject.edit', ["subject"=>$subject, "lp"=>$request->lp]);
    }

    public function update($id, Request $request, Subject $subject) {
        $subject = $subject -> find($id);
        $this->validate($request, [
          'name' => 'required|max:60',
          'short_name' => 'max:15',
          'order_in_the_sheet' => 'integer|between:1,25',
        ]);

        $subject->name = $request->name;
        $subject->short_name = $request->short_name;
        if($request->actual=="true") $subject->actual=1;    else $subject->actual=0;
        $subject->order_in_the_sheet = $request->order_in_the_sheet;
        if( empty($request->order_in_the_sheet) ) $subject->order_in_the_sheet = NULL;
        $subject->expanded = $request->expanded;
        if($request->expanded=="true") $subject->expanded=1;    else $subject->expanded=0;
        $subject->save();

        return $subject->id;
    }

    public function destroy($id, Subject $subject) {
        $subject = $subject -> find($id);
        $subject -> delete();
        return 1;
    }

    public function refreshRow(Request $request, SubjectRepository $subjectRepo) {
        $this->subject = $subjectRepo -> find($request->id);
        return view('subject.row', ["subject"=>$this->subject, "lp"=>$request->lp]);
    }
}