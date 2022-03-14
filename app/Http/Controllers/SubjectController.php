<?php
// ------------------------ (C) mgr inż. Bartłomiej Trojnar; 31.08.2021 ------------------------ //
namespace App\Http\Controllers;
use App\Models\Subject;
use App\Repositories\SubjectRepository;

use App\Models\ExamDescription;
use App\Models\SchoolYear;
use App\Models\Session;
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
        return view('subject.index')
            -> nest('subjectTable', 'subject.table', ["subjects"=>$subjects, "subTitle"=>"", "links"=>true]);
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
          'order_in_the_sheet' => 'integer|between:1,25',
        ]);

        $subject = new Subject;
        $subject->name = $request->name;
        $subject->short_name = $request->short_name;
        if($request->actual=="on") $subject->actual = true; else $subject->actual = false;
        $subject->order_in_the_sheet = $request->order_in_the_sheet;
        if( empty($request->order_in_the_sheet) ) $subject->order_in_the_sheet = NULL;
        if($request->expanded=="on") $subject->expanded = true; else $subject->expanded = false;
        $subject->save();

        return redirect($request->history_view);
    }

    public function change($id) {  session()->put('subjectSelected', $id);   }

    public function show($id, SubjectRepository $subjectRepo, SchoolYearRepository $syR, GradeRepository $gradeRepo, TeacherRepository $tR, GroupRepository $groupRepo, $view='') {
        if(empty(session()->get('subjectView')) || session()->get('subjectView')=='change')  session()->put('subjectView', 'showInfo');
        if($view)  session()->put('subjectView', $view);
        session() -> put('subjectSelected', $id);
        $this->subject = $subjectRepo -> find($id);

        $subjects = $subjectRepo -> getAllSortedAndPaginate();
        list($this->previous, $this->next) = $subjectRepo -> nextAndPreviousRecordId($subjects, $id);

        switch(session()->get('subjectView')) {
            case 'showInfo':   return $this -> showInfo($this->subject);
            case 'showTeachers': return $this -> showTeachers($this->subject);
            case 'showGroups':   return $this -> showGroups($subjectRepo, $syR, $gradeRepo, $tR, $groupRepo);
            case 'showExamDescriptions':   return $this -> showExamDescriptions($subject);
            case 'showTextbooks':   return $this -> showTextbooks($subject);
            default:
                printf('<p style="background: #bb0; color: #f00; font-size: x-large; text-align: center; border: 3px solid red; padding: 5px;">Widok %s nieznany</p>', session()->get('subjectView'));
        }
    }

    private function showInfo($subject) {
        return view('subject.show', ["subject"=>$subject, "previous"=>$this->previous, "next"=>$this->next])
            -> nest('subView', 'subject.showInfo', ["subject"=>$subject]);
    }

    private function showTeachers($subject) {
        $schoolYearRepo = new SchoolYearRepository(new SchoolYear);
        $schoolYears = $schoolYearRepo->getAllSorted();
        $subjectTeachers = $subject -> teachers;
        $schoolYear_id = session() -> get('schoolYearSelected');
        if(!$schoolYear_id) $schoolYear_id=0;
        else for($i=0; $i<sizeof($subjectTeachers); $i++) {
            if($subjectTeachers[$i]->teacher->first_year_id > $schoolYear_id ||
              ($subjectTeachers[$i]->teacher->last_year_id!= "" && $subjectTeachers[$i]->teacher->last_year_id < $schoolYear_id))
                unset($subjectTeachers[$i]);
        }
        $unlearningTeachers = TaughtSubject::unlearningTeachers($subjectTeachers);
        $schoolYearSelectField = view('schoolYear.selectField', ["schoolYears"=>$schoolYears, "schoolYearSelected"=>session()->get('schoolYearSelected'), "name"=>"schoolYear_id" ]);
        return view('subject.show', ["subject"=>$subject, "previous"=>$this->previous, "next"=>$this->next])
            -> nest('subView', 'subject.showTeachers', ["subject"=>$subject, "subjectTeachers"=>$subjectTeachers, "unlearningTeachers"=>$unlearningTeachers, "schoolYearSelectField"=>$schoolYearSelectField]);
    }

    private function showGroups($subjectRepo, $syRepo, $gradeRepo, $teacherRepo, $groupRepo) {
        $gradeSelected = session()->get('gradeSelected');
        $levelSelected = session()->get('levelSelected');
        $dateStart = session() -> get('dateView');
        if(!empty(session() -> get('dateEnd'))) $dateEnd = session() -> get('dateEnd'); else $dateEnd=$dateStart;
        $groups = $groupRepo -> getFilteredAndSorted($gradeSelected, $this->subject->id, $levelSelected, $dateStart, $dateEnd);
        $schoolYear = session()->get('schoolYearSelected');
        if( !empty($schoolYear) )  $schoolYear = $syRepo -> find( $schoolYear );
        $grades = $gradeRepo -> getFilteredAndSorted(substr($schoolYear->date_end, 0, 4), 0);
        $gradeSelectField = view('grade.selectField', ["name"=>"grade_id", "grades"=>$grades, "gradeSelected"=>$gradeSelected, "year"=>substr($schoolYear->date_end, 0, 4)]);
        $levels = array('rozszerzony', 'podstawowy');
        $levelSelectField = view('layouts.levelSelectField', ["levels"=>$levels, "levelSelected"=>$levelSelected]);
        $teachers = $teacherRepo -> getAll();
        $teacherSelected = session()->get('teacherSelected');
        $teacherSelectField = view('teacher.selectField', ["teachers"=>$teachers, "teacherSelected"=>$teacherSelected]);
        $groupTable = view('group.table', ["subTitle"=>"grupy przedmiotu", "subject"=>$this->subject, "groups"=>$groups, "link"=>true, "dateStart"=>$dateStart, "dateEnd"=>$dateEnd,
            "grade_id"=>$gradeSelected, "gradeSelectField"=>$gradeSelectField, "subjectSelectField"=>"", "levelSelectField"=>$levelSelectField, "teacherSelectField"=>$teacherSelectField]);
        return view('subject.show', ["subject"=>$this->subject, "previous"=>$this->previous, "next"=>$this->next, "subView"=>$groupTable]);
    }

    private function showExamDescriptions($subject) {
        $sessionRepo = new SessionRepository(new Session);
        $examDescriptionRepo = new ExamDescriptionRepository(new ExamDescription);
        $sessions = $sessionRepo -> getAllSorted();
        $sessionSelected = session()->get('sessionSelected');
        $sessionSelectField = view('session.selectField', ["sessions"=>$sessions, "sessionSelected"=>$sessionSelected]);

        $examTypes = array('pisemny', 'ustny');
        $examTypeSelected = session()->get('examTypeSelected');;
        $examTypeSelectField = view('examDescription.examTypeSelectField', ["examTypes"=>$examTypes, "examTypeSelected"=>$examTypeSelected]);

        $levels = array('rozszerzony', 'podstawowy', 'nieustalony');
        $levelSelected = session()->get('levelSelected');;
        $levelSelectField = view('layouts.levelSelectField', ["levels"=>$levels, "levelSelected"=>$levelSelected]);

        $examDescriptions = $examDescriptionRepo -> getFilteredAndSorted($subject->id, $sessionSelected, $examTypeSelected, $levelSelected);

        return view('subject.show', ["subject"=>$subject, "previous"=>$this->previous, "next"=>$this->next])
            -> nest('subView', 'examDescription.table', ["examDescriptions"=>$examDescriptions, "subTitle"=>"opisy egzaminów",
                "sessionSelectField"=>$sessionSelectField, "subjectSelectField"=>"",
                "examTypeSelectField"=>$examTypeSelectField, "levelSelectField"=>$levelSelectField]);
    }

    private function showTextbooks($subject) {
        $subjectRepo = new SubjectRepository(new Subject);
        $subjects = $subjectRepo -> getActualAndSorted();
        $subjectSelected = session()->get('subjectSelected');
        $subjectSelectField = view('subject.selectField', ["subjects"=>$subjects, "subjectSelected"=>$subjectSelected]);

        $subTitle = "podręczniki dla przedmiotu";
        $textbooks = $subject -> textbooks;
        return view('subject.show', ["subject"=>$subject, "previous"=>$this->previous, "next"=>$this->next])
            -> nest('subView', 'textbook.table', ["subTitle"=>$subTitle, "textbooks"=>$textbooks, "subjectSelectField"=>$subjectSelectField]);
    }


    public function edit($id, Subject $subject) {
        $subject = $subject -> find($id);
        return view('subject.edit', ["subject"=>$subject]);
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
        if($request->actual=="on") $subject->actual = true; else $subject->actual = false;
        $subject->order_in_the_sheet = $request->order_in_the_sheet;
        if( empty($request->order_in_the_sheet) ) $subject->order_in_the_sheet = NULL;
        if($request->expanded=="on") $subject->expanded = true; else $subject->expanded = false;
        $subject->save();

        return redirect($request->history_view);
    }

    public function destroy($id, Subject $subject) {
        $subject = $subject -> find($id);
        $subject->delete();
        return redirect( $_SERVER['HTTP_REFERER'] );
    }
}
