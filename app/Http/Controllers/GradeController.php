<?php
// ------------------------ (C) mgr inż. Bartłomiej Trojnar; 17.04.2023 ------------------------ //
namespace App\Http\Controllers;
use App\Models\Grade;
use App\Repositories\GradeRepository;

use App\Repositories\DeclarationRepository;
use App\Repositories\EnlargementRepository;
use App\Repositories\GroupRepository;
use App\Repositories\LessonPlanRepository;
use App\Repositories\SchoolRepository;
use App\Repositories\SchoolYearRepository;
use App\Repositories\StudentGradeRepository;
use App\Repositories\StudentNumberRepository;
use App\Repositories\SubjectRepository;
use App\Repositories\TaskRatingRepository;
use App\Repositories\TeacherRepository;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    public function create(Request $request, SchoolRepository $schoolRepo) {
        if($request->version == "forSchool")    return view('grade.createForSchool', ["school_id"=>$request->school_id]);
        if($request->version == "forSchoolYear") return $this -> createRow($schoolRepo);
        return $this -> createRow($schoolRepo);
    }

    private function createRow($schoolRepo) {
        $schools = $schoolRepo -> getAllSorted();
        $schoolSF = view('school.selectField', ["schools"=>$schools, "schoolSelected"=>1]);
        return view('grade.create', ["schoolSF"=>$schoolSF]);
    }

    public function store(Request $request) {
        $this -> validate($request, [
            'year_of_beginning' => 'required|integer|min:1900',
            'year_of_graduation' => 'required|integer|min:1905',
            'symbol' => 'max:2',
            'school_id' => 'required',
        ]);

        $grade = new Grade;
        $grade->year_of_beginning = $request->year_of_beginning;
        $grade->year_of_graduation = $request->year_of_graduation;
        $grade->symbol = $request->symbol;
        $grade->school_id = $request->school_id;
        $grade -> save();
        return $grade->id;
    }

    public function edit(Request $request, Grade $grade, SchoolRepository $schoolRepo) {
        $grade = $grade -> find($request->id);
        if($request->version == "forSchool")    return view('grade.editForSchool', ["grade"=>$grade, "lp"=>$request->lp]);
        return $this -> editRow($grade, $schoolRepo, $request->lp);
    }

    private function editRow($grade, $schoolRepo, $lp) {
        $schools = $schoolRepo -> getAllSorted();
        $schoolSF = view('school.selectField', ["schools"=>$schools, "schoolSelected"=>$grade->school_id]);
        return view('grade.edit', ["grade"=>$grade, "schoolSF"=>$schoolSF, "lp"=>$lp]);
    }

    public function update($id, Request $request, Grade $grade) {
        $grade = $grade -> find($id);
        $this -> validate($request, [
            'year_of_beginning' => 'required|integer|min:1900',
            'year_of_graduation' => 'required|integer|min:1905',
            'symbol' => 'max:2',
            'school_id' => 'required',
        ]);

        $grade->year_of_beginning = $request->year_of_beginning;
        $grade->year_of_graduation = $request->year_of_graduation;
        $grade->symbol = $request->symbol;
        $grade->school_id = $request->school_id;
        $grade -> save();
        return $grade->id;
    }

    public function destroy($id, Grade $grade)  {
        $grade = $grade -> find($id);
        $grade -> delete();
        return 1;
    }

    public function refreshRow(Request $request, GradeRepository $gradeRepo, SchoolYearRepository $schoolYearRepo) {
        $this->grade = $gradeRepo -> find($request->id);

        $schoolYearSelected = session()->get('schoolYearSelected');
        if($schoolYearSelected) {
            $schoolYear = $schoolYearRepo -> find($schoolYearSelected);
            $year = substr($schoolYear->date_end, 0, 4);
        }
        else $year=0;

        if($request->version == "forSchool")    return view('grade.rowForSchool', ["grade"=>$this->grade, "year"=>$year, "lp"=>$request->lp]);
        return view('grade.row', ["grade"=>$this->grade, "year"=>$year, "lp"=>$request->lp]);
    }

    public function index(GradeRepository $gradeRepo, SchoolRepository $schoolRepo, SchoolYearRepository $schoolYearRepo)   {
        $schoolYearSelected = session()->get('schoolYearSelected');
        if($schoolYearSelected) {
            $schoolYear = $schoolYearRepo -> find($schoolYearSelected);
            $year = substr($schoolYear->date_end, 0, 4);
        }
        else $year=0;
        $schoolSelected = session()->get('schoolSelected');
        $grades = $gradeRepo -> getFilteredAndSortedAndPaginate($year, $schoolSelected);
        $schools = $schoolRepo -> getAllSorted();
        $schoolSF = view('school.selectField', ["schools"=>$schools, "schoolSelected"=>$schoolSelected]);
        $schoolYears = $schoolYearRepo -> getAllSorted();
        $schoolYearSF = view('schoolYear.selectField', ["schoolYears"=>$schoolYears, "schoolYearSelected"=>$schoolYearSelected, "name"=>"school_year_id"]);
        return view('grade.index', ["grades"=>$grades, "schoolYearSF"=>$schoolYearSF, "schoolSF"=>$schoolSF, "year"=>$year]);
    }

    public function orderBy($column)    {
        if(session()->get('GradeOrderBy[0]') == $column)
          if(session()->get('GradeOrderBy[1]') == 'desc')
            session()->put('GradeOrderBy[1]', 'asc');
          else
            session()->put('GradeOrderBy[1]', 'desc');
        else
        {
          session()->put('GradeOrderBy[2]', session()->get('GradeOrderBy[0]'));
          session()->put('GradeOrderBy[0]', $column);
          session()->put('GradeOrderBy[3]', session()->get('GradeOrderBy[1]'));
          session()->put('GradeOrderBy[1]', 'asc');
        }
        return redirect( $_SERVER['HTTP_REFERER'] );
    }

    public function change($id) {  session()->put('gradeSelected', $id);  }

    public function show($id, GradeRepository $gradeRepo, SchoolYearRepository $syR, StudentGradeRepository $sgR, StudentNumberRepository $snR,
            GroupRepository $gR, LessonPlanRepository $lpR, DeclarationRepository $dR, SubjectRepository $subR, teacherRepository $tR,
            EnlargementRepository $eR, TaskRatingRepository $tRR,  $view='') {
        if( empty(session()->get('gradeView')) )  session()->put('gradeView', 'info');
        if($view)  session()->put('gradeView', $view);
        if(!empty($id)) {
            $this->grade = $gradeRepo -> find($id);
            session()->put('gradeSelected', $id);
        }

        $schoolYearSelected = session()->get('schoolYearSelected');
        if($schoolYearSelected) {
            $schoolYear = $syR -> find($schoolYearSelected);
            $year = substr($schoolYear->date_end, 0, 4);
        }
        else $year=0;
        $schoolSelected = session()->get('schoolSelected');
        $grades = $gradeRepo -> getFilteredAndSorted($year, $schoolSelected);
        list($this->previous, $this->next) = $gradeRepo -> nextAndPreviousRecordId($grades, session()->get('gradeSelected'));

        // pobranie informacji o roku szkolnym (aby wyświetlać rocznik klasy, jeżeli jest wybrany)
        $this->year = 0;
        if( !empty(session()->get('schoolYearSelected')) ) {
            $schoolYear = $syR -> find(session()->get('schoolYearSelected'));
            $this->year = substr($schoolYear->date_end, 0, 4);
        }

        switch(session()->get('gradeView')) {
            case 'info':        return $this -> showInfo();
            case 'uczniowie':   return $this -> showStudents($syR, $sgR);
            case 'daneuczniow': return $this -> showStudentsAll();
            case 'numery':      return $this -> showNumbers($syR, $snR);
            case 'grupy':       return $this -> showGroups($subR, $tR, $gR);
            case 'planlekcji':  return $this -> showLessonPlan($gR, $lpR, $syR);
            case 'nauczyciele': return $this -> showTeachers();
            case 'oceny':       return $this -> showRatings();
            case 'deklaracje':  return $this -> showDeclarations($dR);
            case 'zadania':     return $this -> showTasks($tRR);
            case 'rozszerzenia':return $this -> showEnlargements($eR);
            default:
              printf('<p style="background: #bb0; color: #f00; font-size: x-large; text-align: center; border: 3px solid red; padding: 5px;">Widok %s nieznany</p>', session()->get('gradeView'));
        }
    }

    private function showInfo() {
        $subView = view('grade.showInfo', ["grade"=>$this->grade]);
        return view('grade.show', ["css"=>"", "js"=>"", "previous"=>$this->previous, "next"=>$this->next, "grade"=>$this->grade, "subView"=>$subView, "year"=>$this->year]);
    }

    private function showStudents($schoolYearRepo, $studentGradeRepo) { // funkcja przygotowująca i wyświetlająca widok przynależności uczniów do klasy
        $start = session()->get('dateView');
        $end = session()->get('dateEnd');
        if(!$end)   $end = $start;
        $schoolYearEnd = $schoolYearRepo -> getSchoolYearIdForDate($this->grade->year_of_graduation.'-01-01');

        $grades[0] = $this->grade->id;
        $studentGrades = $studentGradeRepo -> getStudentsFromGrades($grades);
        $tableForGrade = view('studentGrade.tableForGrade', ["studentGrades"=>$studentGrades, "yearOfStudy"=>$this->year, "grade"=>$this->grade, "schoolYearEnd"=>$schoolYearEnd, "start"=>$start, "end"=>$end]);

        $css = "grade/studentGrades.css";
        $js = "grade/studentGrades.js";
        return view('grade.show', ["grade"=>$this->grade, "year"=>$this->year, "previous"=>$this->previous, "next"=>$this->next, "css"=>$css, "js"=>$js, "subView"=>$tableForGrade]);
    }

    private function showStudentsAll() {  // funkcja przygotowująca i wyświetlająca widok danych osobowych wszystkich uczniów, którzy w zadanym dniu należą do klasy
        foreach($this->grade -> students as $studentClass) {
            if( $studentClass->start <= session()->get('dateView') && $studentClass->end >= session()->get('dateView') )
              $students[] = $studentClass->student;
            else $studentsOutOfDate[] = $studentClass->student;
         }
         if(empty($students)) $students=0;
         if(empty($studentsOutOfDate)) $studentsOutOfDate=0;

         $selectedSex = 'kobieta';
         $sexSF = view('student.sexSelectField', ["sex"=>$selectedSex]);
         $studentsTable = view('student.table', ["grade"=>$this->grade, "students"=>$students, "subTitle"=>"aktualni uczniowie klasy", "showDateView"=>true, "sexSF"=>$sexSF]);
         $studentsOutOfDateTable = view('student.table', ["grade"=>$this->grade, "students"=>$studentsOutOfDate, "subTitle"=>"pozostali uczniowie klasy", "showDateView"=>false]);
         $js = "student/forGrade.js";
         return view('grade.show', ["grade"=>$this->grade, "year"=>$this->year, "previous"=>$this->previous, "next"=>$this->next, "css"=>"", "js"=>$js, "subView"=>$studentsTable, "subView2"=>$studentsOutOfDateTable]);
    }
/*
    private function showNumbers($schoolYearRepo, $studentNumberRepo) {
        $schoolYears = $schoolYearRepo -> getAllSorted();
        if(session()->get('schoolYearSelected')) {
            $schoolYear = $schoolYearRepo -> find( session()->get('schoolYearSelected') );
            $schoolYearSF = view('schoolYear.selectField', ["schoolYears"=>$schoolYears, "schoolYearSelected"=>$schoolYear->id, "name"=>"school_year_id" ]);
            $studentNumbers = $studentNumberRepo -> getGradeNumbersForSchoolYear($this->grade->id, $schoolYear->id);
        }
        else {
            $schoolYear = $schoolYearRepo -> find( $schoolYearRepo -> getSchoolYearIdForDate(date('Y-m-d')) );
            $schoolYearSF = view('schoolYear.selectField', ["schoolYears"=>$schoolYears, "schoolYearSelected"=>0, "name"=>"school_year_id" ]);
            $studentNumbers = $studentNumberRepo -> getGradeNumbers($this->grade->id);
        }
        $yearOfStudy = substr($schoolYear->date_end,0,4) - ($this->grade->year_of_beginning+1);
        if(substr($schoolYear->date_end,5,2)) $yearOfStudy++;

        $tableForGrade = view('studentNumber.tableForGrade', ["schoolYearSF"=>$schoolYearSF, "studentNumbers"=>$studentNumbers, "grade"=>$this->grade, "dateView"=>session()->get('dateView')]);
        $count = count($studentNumbers);
        $sectionForGrade = view('studentNumber.sectionForGrade', ["grade"=>$this->grade, "tableForGrade"=>$tableForGrade, "count"=>$count, "yearOfStudy"=>$yearOfStudy]);
        $css = "grade/studentNumbers.css";
        $js = "grade/studentNumbers.js";
        return view('grade.show', ["grade"=>$this->grade, "css"=>$css, "js"=>$js, "previous"=>$this->previous, "next"=>$this->next, "year"=>$this->year, "subView"=>$sectionForGrade]);
    }
*/
    private function showGroups($subjectRepo, $teacherRepo, $groupRepo) {
        $subjectSelected = session()->get('subjectSelected');
        $subjects = $subjectRepo -> getActualAndSorted();
        $subjectSF = view('subject.selectField', ["subjects"=>$subjects, "subjectSelected"=>$subjectSelected]);

        $levels = array('rozszerzony', 'podstawowy');
        $levelSelected = session()->get('levelSelected');
        $levelSF = view('layouts.levelSelectField', ["levels"=>$levels, "levelSelected"=>$levelSelected]);

        $teacherSelected = session()->get('teacherSelected');
        $teachers = $teacherRepo -> getFiltered( session()->get('schoolYearSelected') );
        $teacherSF = view('teacher.selectField', ["teachers"=>$teachers, "teacherSelected"=>$teacherSelected]);

        $start = session() -> get('dateView');
        if(!empty(session() -> get('dateEnd'))) $end = session() -> get('dateEnd'); else $end=$start;
        $groups = $groupRepo -> getFilteredAndSortedAndPaginate($this->grade->id, $subjectSelected, $levelSelected, $start, $end, $teacherSelected);

        $groupTable = view('group.table', ["version"=>"forGrade", "subTitle"=>"grupy klasy", "groups"=>$groups, "links"=>true, "start"=>$start, "end"=>$end, "grade_id"=>$this->grade->id,
            "gradeSF"=>"", "subjectSF"=>$subjectSF, "levelSF"=>$levelSF, "teacherSF"=>$teacherSF, "schoolYearSF"=>""]);
        $js = "group/operations.js";

        return view('grade.show', ["grade"=>$this->grade, "year"=>$this->year, "previous"=>$this->previous, "next"=>$this->next, "css"=>"", "js"=>$js, "subView"=>$groupTable]);
    }

    private function showLessonPlan($groupRepo, $lessonPlanRepo, $schoolYearRepo) {
        $gradeLessons = $lessonPlanRepo -> getGradeLessons($this->grade->id);
        $dateView = session()->get('dateView'); if($dateView=="") $dateView = date('Y-m-d');
        $groups = $groupRepo -> getGradeGroups($this->grade->id);
        $studyYear = substr($dateView,0,4) - $this->grade->year_of_beginning;
        $schoolYearEnds = $schoolYearRepo -> getSchoolYearEnds($this->grade->year_of_beginning, $this->grade->year_of_graduation);     // znalezienie dat końcowych roku szkolnego w czasie istnienia klasy
        $gradePlan = view('lessonPlan.gradePlan', ["gradeLessons"=>$gradeLessons, "groups"=>$groups, "grade"=>$this->grade, "dateView"=>$dateView, "studyYear"=>$studyYear, "schoolYearEnds"=>$schoolYearEnds]);
        $js = "lessonPlan/forGrade.js";
        return view('grade.show', ["grade"=>$this->grade, "year"=>$this->year, "previous"=>$this->previous, "next"=>$this->next,  "css"=>"", "js"=>$js, "subView"=>$gradePlan]);
    }
/*
    private function showTeachers() {
        $teacherRepo = new TeacherRepository(new Teacher);
        $teachers = $teacherRepo -> getTeachersForGrade($this->grade->id);

        $teacherTable = view('teacher.shortTable', ["teachers"=>$teachers, "subTitle"=>"nauczyciele klasy"]);

        return view('grade.show', ["grade"=>$this->grade, "year"=>$this->year, "previous"=>$this->previous, "next"=>$this->next,
            "css"=>"", "js"=>"", "subView"=>$teacherTable]);
    }
*/
    private function showTasks($taskRatingRepo) {
        $taskRatings = $taskRatingRepo -> getGradeTaskRatings($this->grade->id);
        $diaryYesNoSelected = session() -> get('diaryYesNoSelected');
        $diarySF = view('layouts.yesNoSelectField', ["fieldName"=>"diaryYesNo", "valueSelected"=>$diaryYesNoSelected]);
        $taskRatingTable = view('taskRating.tableForGrade', ["diarySF"=>$diarySF, "taskRatings"=>$taskRatings]);
        $css = "taskRating/style.css";
        $js = "taskRating/forGrade.js";
        return view('grade.show', ["grade"=>$this->grade, "previous"=>$this->previous, "next"=>$this->next, "css"=>$css, "js"=>$js, "subView"=>$taskRatingTable]);
    }
/*
    private function showRatings() {
        $ratingsTable = view('rating.tableForGrade', []);
        return view('grade.show', ["grade"=>$this->grade, "previous"=>$this->previous, "next"=>$this->next, "css"=>"", "js"=>"", "subView"=>$ratingsTable]);
    }
*/
    private function showEnlargements($enlargementRepo) {
        $enlargements = $enlargementRepo -> getFilteredAndSorted($this->grade->id);
        $enlargementsTable = view('enlargement.tableForGrade', ["enlargements"=>$enlargements]);
        $js = "enlargement/forGrade.js";
        return view('grade.show', ["css"=>"", "js"=>$js, "previous"=>$this->previous, "next"=>$this->next, "grade"=>$this->grade, "subView"=>$enlargementsTable, "year"=>$this->year]);
    }
/*
    private function showDeclarations($declarationRepo) {
        $sessionSelected = session()->get('sessionSelected');
        $declarations = $declarationRepo -> getFilteredAndSorted($sessionSelected, $this->grade->id, 0);
        $declarationsTable = view('declaration.tableForGrade', ["declarations"=>$declarations]);
        $css = "";
        $js = "declaration/operations.js";
        return view('grade.show', ["grade"=>$this->grade, "year"=>$this->year, "previous"=>$this->previous, "next"=>$this->next, "subView"=>$declarationsTable, "css"=>$css, "js"=>$js]);
    }

    public function getDates($id, Grade $grade) {
        $grade = $grade -> find($id);
        for($i = $grade->year_of_beginning; $i<$grade->year_of_graduation; $i++) {
            $dates[] = $i."-09-01";
            $dates[] = ($i+1)."-08-31";
        }
        return $dates;
    }
    */
}