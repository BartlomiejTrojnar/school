<?php
// ------------------------ (C) mgr inż. Bartłomiej Trojnar; 02.07.2022 ------------------------ //
namespace App\Http\Controllers;
use App\Models\SchoolYear;
use App\Repositories\SchoolYearRepository;
use App\Repositories\GradeRepository;
use App\Repositories\GroupRepository;
use App\Repositories\SchoolRepository;
use App\Repositories\StudentRepository;
use App\Repositories\SubjectRepository;
use App\Repositories\TeacherRepository;
use Illuminate\Http\Request;

class SchoolYearController extends Controller
{
    public function index(SchoolYearRepository $schoolYearRepo) {
        $schoolYears = $schoolYearRepo->getPaginateSorted();
        $js = 'schoolYear/index.js';
        return view('schoolYear.index', ["schoolYears"=>$schoolYears, "js"=>$js]);
    }

    public function create() { return view('schoolYear.create'); }

    public function store(Request $request) {
        $this -> validate($request, [
            'date_start' => 'required|date_format:"Y-m-d"',
            'date_end' => 'required|date_format:"Y-m-d"',
            'date_of_classification_of_the_last_grade' => 'date',
            'date_of_graduation_of_the_last_grade' => 'date',
            'date_of_classification' => 'date',
            'date_of_graduation' => 'date',
        ]);

        $sy = new SchoolYear;
        $sy->date_start = $request->date_start;
        $sy->date_end = $request->date_end;
        $sy->date_of_classification_of_the_last_grade = $request->date_of_classification_of_the_last_grade;
        $sy->date_of_graduation_of_the_last_grade = $request->date_of_graduation_of_the_last_grade;
        $sy->date_of_classification = $request->date_of_classification;
        $sy->date_of_graduation = $request->date_of_graduation;
        $sy->save();

        return $sy->id;
    }

    public function change($id) {  session()->put('schoolYearSelected', $id);  }

    public function show($id, SchoolYearRepository $schoolYearRepo, GradeRepository $gradeRepo, GroupRepository $groupRepo, StudentRepository $studentRepo, SchoolRepository $schoolRepo, SubjectRepository $subjectRepo, TeacherRepository $teacherRepo, $view='') {
        if(empty( session()->get('schoolYearView') ))  session()->put('schoolYearView', 'showInfo');
        if($view) session()->put('schoolYearView', $view);
        $this->schoolYear = $schoolYearRepo -> find($id);
        session()->put('schoolYearSelected', $id);

        $schoolYears = $schoolYearRepo->getPaginateSorted();
        list($this->previous, $this->next) = $schoolYearRepo -> nextAndPreviousRecordId($schoolYears, $id);

        switch(session()->get('schoolYearView')) {
            case 'showInfo':     return $this -> showInfo($gradeRepo, $studentRepo);
            case 'showGrades':   return $this -> showGrades($gradeRepo, $schoolRepo);
            case 'showStudents': return $this -> showStudents($studentRepo);
            case 'showTeachers': return $this -> showTeachers($teacherRepo);
            case 'showGroups':   return $this -> showGroups($gradeRepo, $subjectRepo, $teacherRepo, $groupRepo);
            case 'showTextbooks': return $this -> showTextbooks($schoolYearRepo);
            default:
                printf('<p style="background: #bb0; color: #f00; font-size: x-large; text-align: center; border: 3px solid red; padding: 5px;">Widok %s nieznany</p>', session()->get('schoolYearView'));
            break;
        }
    }

    private function showInfo($gradeRepo, $studentRepo) {
        $countStudents = $studentRepo -> countStudentsByDates($this->schoolYear->date_start, $this->schoolYear->date_end);
        $countGrades = $gradeRepo -> countGradesInYear(substr($this->schoolYear->date_end, 0, 4));
        $schoolYearInfo = view('schoolYear.showInfo', ["schoolYear"=>$this->schoolYear, "countStudents"=>$countStudents, "countGrades"=>$countGrades]);
        $css = "";
        $js = "";
        return view('schoolYear.show', ["schoolYear"=>$this->schoolYear, "previous"=>$this->previous, "next"=>$this->next, "subView"=>$schoolYearInfo, "css"=>$css, "js"=>$js]);
    }

    private function showGrades($gradeRepo, $schoolRepo) {
        $schoolSelected = session()->get('schoolSelected');
        $schools = $schoolRepo -> getAllSorted();
        $schoolSelectField = view('school.selectField', ["schools"=>$schools, "schoolSelected"=>$schoolSelected]);

        $year = substr($this->schoolYear->date_end,0,4);
        $schoolSelected = session()->get('schoolSelected');
        $grades = $gradeRepo -> getFilteredAndSortedAndPaginate($year, $schoolSelected);

        $gradeTable = view('grade.table', ["grades"=>$grades, "schoolSelectField"=>$schoolSelectField, "year"=>$year, "schoolYearSelectField"=>""]);
        $css = "";
        $js = "schoolYear/grade.js";
        return view('schoolYear.show', ["schoolYear"=>$this->schoolYear, "previous"=>$this->previous, "next"=>$this->next, "subView"=>$gradeTable, "css"=>$css, "js"=>$js]);
    }

    private function showTeachers($teacherRepo) {
        $teachers = $teacherRepo -> getTeachersInYear($this->schoolYear->id);
        $teachersTable = view('teacher.tableForSchoolYear', ["teachers"=>$teachers]);
        return view('schoolYear.show', ["schoolYear"=>$this->schoolYear, "previous"=>$this->previous, "next"=>$this->next, "css"=>"", "js"=>"", "subView"=>$teachersTable]);
    }

    private function showGroups($gradeRepo, $subjectRepo, $teacherRepo, $groupRepo) {
        $grades = $gradeRepo -> getFilteredAndSorted(substr($this->schoolYear->date_end, 0, 4), 0);
        $gradeID = session()->get('gradeSelected');
        $gradeSelectField = view('grade.selectField', ["name"=>"grade_id", "grades"=>$grades, "gradeSelected"=>$gradeID, "year"=>substr($this->schoolYear->date_end, 0, 4)]);

        $subjects = $subjectRepo -> getActualAndSorted();
        $subjectID = session()->get('subjectSelected');
        $subjectSelectField = view('subject.selectField', ["subjects"=>$subjects, "subjectSelected"=>$subjectID]);

        $levels = array('rozszerzony', 'podstawowy');
        $levelID = session()->get('levelSelected');
        $levelSelectField = view('layouts.levelSelectField', ["levels"=>$levels, "levelSelected"=>$levelID]);

        $teachers = $teacherRepo -> getTeachersInYear($this->schoolYear);
        $teacherID = session()->get('teacherSelected');
        $teacherSelectField = view('teacher.selectField', ["teachers"=>$teachers, "teacherSelected"=>$teacherID]);

        $subTitle = "grupy w roku szkolnym";
        $groups = $groupRepo -> getFilteredAndSorted($gradeID, $subjectID, $levelID, $this->schoolYear->date_start, $this->schoolYear->date_end, $teacherID);
        $groupsTable = view('group.tableForSchoolYear', ["subTitle"=>$subTitle, "groups"=>$groups, "links"=>true, "start"=>$this->schoolYear->date_start, "end"=>$this->schoolYear->date_end,
            "gradeSelectField"=>$gradeSelectField, "subjectSelectField"=>$subjectSelectField, "levelSelectField"=>$levelSelectField, "teacherSelectField"=>$teacherSelectField, "version"=>"forSchoolYear"]);

        return view('schoolYear.show', ["schoolYear"=>$this->schoolYear, "previous"=>$this->previous, "next"=>$this->next, "css"=>"", "js"=>"", "subView"=>$groupsTable]);
    }

    private function showStudents($studentRepo) {
        $students = $studentRepo -> getFilteredAndSorted(0, 0, $this->schoolYear);
        $studentsTable = view('student.tableForSchoolYear', ["students"=>$students]);
        return view('schoolYear.show', ["schoolYear"=>$this->schoolYear, "previous"=>$this->previous, "next"=>$this->next, "css"=>"", "js"=>"", "subView"=>$studentsTable]);
    }

    private function showTextbooks($schoolYearRepo) {
        $textbookChoices = $schoolYearRepo -> find($this->schoolYear->id) -> textbookChoices;
        $textbooks = [];
        foreach($textbookChoices as $textbookChoice)  $textbooks[] = $textbookChoice->textbook;
        $textbooksTable = view('textbook.table', ["textbooks"=>$textbooks, "subTitle"=>"Wybrane podręczniki", "subjectSelectField"=>""]);
        return view('schoolYear.show', ["schoolYear"=>$this->schoolYear, "previous"=>$this->previous, "next"=>$this->next, "css"=>"", "js"=>"", "subView"=>$textbooksTable]);
    }

    public function edit(Request $request, SchoolYearRepository $schoolYearRepo) {
        $schoolYear = $schoolYearRepo -> find($request->id);
        return view('schoolYear.edit', ["schoolYear"=>$schoolYear, "lp"=>$request->lp]);
    }

    public function update(Request $request, SchoolYear $schoolYear) {
        $schoolYear = SchoolYear::find($request->id);
        $this->validate($request, [
            'date_start' => 'required|date_format:"Y-m-d"',
            'date_end' => 'required|date_format:"Y-m-d"',
            'date_of_classification_of_the_last_grade' => 'date',
            'date_of_graduation_of_the_last_grade' => 'date',
            'date_of_classification' => 'date',
            'date_of_graduation' => 'date',
        ]);

        $schoolYear->date_start = $request->date_start;
        $schoolYear->date_end = $request->date_end;
        $schoolYear->date_of_classification_of_the_last_grade = $request->date_of_classification_of_the_last_grade;
        $schoolYear->date_of_graduation_of_the_last_grade = $request->date_of_graduation_of_the_last_grade;
        $schoolYear->date_of_classification = $request->date_of_classification;
        $schoolYear->date_of_graduation = $request->date_of_graduation;
        $schoolYear->save();

        return $schoolYear->id;
    }

    public function destroy($id, SchoolYear $schoolYear) {
        $schoolYear = SchoolYear::find($id);
        $schoolYear -> delete();
        return 1;
    }

    public function refreshRow(Request $request, SchoolYearRepository $schoolYearRepo) {
        $this->schoolYear = $schoolYearRepo -> find($request->id);
        return view('schoolYear.row', ["sy"=>$this->schoolYear, "lp"=>$request->lp]);
    }
}