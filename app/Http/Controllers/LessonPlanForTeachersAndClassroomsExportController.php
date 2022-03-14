<?php
// ------------------------ (C) mgr inż. Bartłomiej Trojnar; 23.02.2022 ------------------------ //
namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use App\Http\Requests;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

use App\Models\Classroom;
use App\Models\LessonPlan;
use App\Models\SchoolYear;
use App\Models\Teacher;
use App\Repositories\ClassroomRepository;
use App\Repositories\LessonPlanRepository;
use App\Repositories\SchoolYearRepository;
use App\Repositories\TeacherRepository;

class LessonPlanForTeachersAndClassroomsExportController extends Controller
{
    public function exportPlan(Request $request)  {
        // ustalenie daty dla której generowany jest plan i roku dla obliczania roku nauki
        $this->dateView = $request->dateView;
        $this->year = substr($this->dateView, 0, 4);
        if(substr($this->dateView, 5, 2) > 7) $this->year++;

        $this->lessonPlanRepo = new LessonPlanRepository(new LessonPlan);

        // utworzenie arkusza z planem lekcji
        Excel::create('plan_lekcji', function($excel) {
            // Set the title
            $excel->setTitle('plan lekcji dla II LO');
            // Chain the setters
            $excel->setCreator('Group Manager by Bartłomiej Trojnar')
                  ->setCompany('II Liceum Ogólnokształcące w Łańcucie');
            // Call them separately
            $excel->setDescription('Wygenerowano za pomocą aplikacji Group Manager');

            $excel->sheet('sale', function($sheet) {
                $this->sheet = $sheet;
                $this -> createClassroomSheet();
            });
            $excel->sheet('nauczyciele', function($sheet) {
                $this->sheet = $sheet;
                $this -> createTeachersSheet();
            });
        })->export('xlsx');
    }

    private function createTeachersSheet() {
        // utworzenie pierwszej kolumny
        $this -> createFirstCollumn();

        // znalezienie roku szkolnego zgodnego z aktualnie wybraną datą w programie
        $schoolYearRepo = new SchoolYearRepository(new SchoolYear);
        $school_year_id = $schoolYearRepo -> getSchoolYearIdForDate($this->dateView);

        // wyszukanie nauczycieli uczących w odnalezionym roku szkolnym
        $teacherRepo = new TeacherRepository(new Teacher);
        $orderBy1 = session()->get('TeacherOrderBy[0]');
        session() -> put('TeacherOrderBy[0]', 'order');
        session() -> put('TeacherOrderBy[1]', 'asc');
        session() -> put('TeacherOrderBy[2]', 'last_name');
        session() -> put('TeacherOrderBy[3]', 'asc');
        session() -> put('TeacherOrderBy[4]', 'first_name');
        $teachers = $teacherRepo -> getTeachersInYear($school_year_id);
        session() -> put('TeacherOrderBy[0]', $orderBy1);

        $row=1; $col=2;
        foreach($teachers as $teacher) {
            // wprowadzenie imienia i nazwiskoa nauczyciela oraz sformatowanie komórki
            $this->sheet -> setCellValueByColumnAndRow($col, $row, $teacher->first_name." ".$teacher->last_name);
            $this->sheet -> getStyleByColumnAndRow($col, $row) -> getFont() -> setbold(true) -> setSize(14);
            $this->sheet -> getStyleByColumnAndRow($col, $row) -> getAlignment() -> setVertical('center') -> setTextRotation(90);
            $this->sheet->getColumnDimensionByColumn($col)->setAutoSize(true);

            // wprowadzenie i sformatowanie komórek dla każdej godziny w tygodniu
            $row=2;
            for($lessonHour=1; $lessonHour<=75; $lessonHour++, $row++) {
                $this -> setTeacherLessonForHour($col, $row, $teacher->id, $lessonHour);
                if($lessonHour % 15 == 10) $lessonHour += 5;
            }
            $col++; $row=1;
        }
        $this -> sheetTeachersFormat($col-1);
    }

    private function setTeacherLessonForHour($col, $row, $teacher_id, $lessonHour_id) {	// znalezienie lekcji nauczyciela dla podanej godziny i daty oraz wprowadzenie tego do arkusza
        $lessons = $this->lessonPlanRepo -> findTeacherLessonsForHour($teacher_id, $lessonHour_id, $this->dateView);
        if(count($lessons)==0) return;     // jeżeli nauczyciel nie ma lekcji na wskazanej godzinie
        if(count($lessons)>1)  {    // jeżeli jest więcej niż jedna lekcja
            $this->sheet -> setCellValueByColumnAndRow($col, $row, ">1 lekcja");
            return;
        }
        // jeżeli nauczyciel na wskazanej godzinie ma jedną lekcję
        foreach($lessons as $lesson) {
            $background = false;
            $grades = "";
            foreach($lesson->group->grades as $groupGrade) {
                if(!$grades) $grades = $this->year-$groupGrade->grade->year_of_beginning;
                $grades .= $groupGrade->grade->symbol;
                if( date('Y-m-d', strtotime('+7 day', strtotime($lesson->date_start))) >=$this->dateView) $background=true;
            }
            $grades .= " ".$lesson->classroom['name'];
            $this->sheet -> setCellValueByColumnAndRow($col, $row, $grades);
            if($background) $this->sheet -> getStyleByColumnAndRow($col, $row) -> applyFromArray(array( 'fill' => array(
                'type'  => \PHPExcel_Style_Fill::FILL_SOLID,    'color' => array('rgb' => 'eeeeee') ) ));
        }
    }    

    private function sheetTeachersFormat($col) {
        $this->sheet -> setWidth(chr(65), 8);
        $this->sheet -> getStyleByColumnAndRow(0, 1, $col, 51) -> getAlignment() -> setVertical('center') -> setHorizontal('center');

        // obramowanie
        $this->sheet -> getStyleByColumnAndRow(0, 1, $col, 51) -> applyFromArray( array( 'borders' => array( 'allborders' => array(
            'style' => 'thin',
            'color' => array('rgb' => '000000')
        ))));
        $this->sheet -> getStyleByColumnAndRow(0, 1, $col, 51) -> applyFromArray(   array( 'borders' => array( 'top' => array( 'style' => 'medium' ))));
        $this->sheet -> getStyleByColumnAndRow(0, 1, $col, 51) -> applyFromArray(   array( 'borders' => array( 'right' => array( 'style' => 'medium' ))));
        $this->sheet -> getStyleByColumnAndRow(0, 1, $col, 51) -> applyFromArray(   array( 'borders' => array( 'bottom' => array( 'style' => 'medium' ))));
        $this->sheet -> getStyleByColumnAndRow(0, 1, $col, 51) -> applyFromArray(   array( 'borders' => array( 'left' => array( 'style' => 'medium' ))));

        $this->sheet -> getStyleByColumnAndRow(0, 1, $col, 41) -> applyFromArray(   array( 'borders' => array( 'bottom' => array( 'style' => 'medium' ))));
        $this->sheet -> getStyleByColumnAndRow(0, 1, $col, 31) -> applyFromArray(   array( 'borders' => array( 'bottom' => array( 'style' => 'medium' ))));
        $this->sheet -> getStyleByColumnAndRow(0, 1, $col, 21) -> applyFromArray(   array( 'borders' => array( 'bottom' => array( 'style' => 'medium' ))));
        $this->sheet -> getStyleByColumnAndRow(0, 1, $col, 11) -> applyFromArray(   array( 'borders' => array( 'bottom' => array( 'style' => 'medium' ))));
        $this->sheet -> getStyleByColumnAndRow(0, 1, $col, 1) -> applyFromArray(   array( 'borders' => array( 'bottom' => array( 'style' => 'medium' ))));
        $this->sheet -> getStyleByColumnAndRow(0, 1, 1, 51) -> applyFromArray(   array( 'borders' => array( 'right' => array( 'style' => 'medium' ))));

        // układ strony
        $this->sheet -> getPageSetup() -> setOrientation(\PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
        $this->sheet -> getPageMargins() -> setTop(0.4) -> setLeft(0.3) -> setRight(0.3) -> setBottom(0.3);
        $this->sheet  -> getPageSetup() -> setFitToWidth(0);
    }

    private function createClassroomSheet() {
        // utworzenie pierwszej kolumny
        $this -> createFirstCollumn();

        // znalezienie sal lekcyjnych w bazie danych
        $classroomRepo = new ClassroomRepository(new Classroom);
        $orderBy1 = session()->get('ClassroomOrderBy[0]');
        session() -> put('ClassroomOrderBy[0]', 'name');
        $classrooms = $classroomRepo -> getAllSorted();
        session() -> put('ClassroomOrderBy[0]', $orderBy1);

        $row=1; $col=2;
        foreach($classrooms as $classroom) {
            // wprowadzenie nazwy sali oraz sformatowanie komórki
            $this->sheet -> setCellValueByColumnAndRow($col, $row, $classroom->name);
            $this->sheet -> getStyleByColumnAndRow($col, $row) -> getFont() -> setbold(true) -> setSize(14);
            $this->sheet -> getStyleByColumnAndRow($col, $row) -> getAlignment() -> setVertical('center') -> setTextRotation(90);
            $this->sheet->getColumnDimensionByColumn($col)->setAutoSize(true);

            // wprowadzenie i sformatowanie komórek dla każdej godziny w tygodniu
            $row=2;
            for($lessonHour=1; $lessonHour<=75; $lessonHour++, $row++) {
                $this -> setClassroomLessonForHour($col, $row, $classroom->id, $lessonHour);
                if($lessonHour % 15 == 10) $lessonHour += 5;
            }
            $col++; $row=1;
        }
        $this -> sheetClassroomsFormat($col-1);
    }

    private function sheetClassroomsFormat($col) {
        $this->sheet -> setWidth(chr(65), 8);
        $this->sheet -> getStyleByColumnAndRow(0, 1, $col, 51) -> getAlignment() -> setVertical('center') -> setHorizontal('center');

        // obramowanie
        $this->sheet -> getStyleByColumnAndRow(0, 1, $col, 51) -> applyFromArray( array( 'borders' => array( 'allborders' => array(
            'style' => 'thin',
            'color' => array('rgb' => '000000')
        ))));
        $this->sheet -> getStyleByColumnAndRow(0, 1, $col, 51) -> applyFromArray(   array( 'borders' => array( 'top' => array( 'style' => 'medium' ))));
        $this->sheet -> getStyleByColumnAndRow(0, 1, $col, 51) -> applyFromArray(   array( 'borders' => array( 'right' => array( 'style' => 'medium' ))));
        $this->sheet -> getStyleByColumnAndRow(0, 1, $col, 51) -> applyFromArray(   array( 'borders' => array( 'bottom' => array( 'style' => 'medium' ))));
        $this->sheet -> getStyleByColumnAndRow(0, 1, $col, 51) -> applyFromArray(   array( 'borders' => array( 'left' => array( 'style' => 'medium' ))));

        $this->sheet -> getStyleByColumnAndRow(0, 1, $col, 41) -> applyFromArray(   array( 'borders' => array( 'bottom' => array( 'style' => 'medium' ))));
        $this->sheet -> getStyleByColumnAndRow(0, 1, $col, 31) -> applyFromArray(   array( 'borders' => array( 'bottom' => array( 'style' => 'medium' ))));
        $this->sheet -> getStyleByColumnAndRow(0, 1, $col, 21) -> applyFromArray(   array( 'borders' => array( 'bottom' => array( 'style' => 'medium' ))));
        $this->sheet -> getStyleByColumnAndRow(0, 1, $col, 11) -> applyFromArray(   array( 'borders' => array( 'bottom' => array( 'style' => 'medium' ))));
        $this->sheet -> getStyleByColumnAndRow(0, 1, $col, 1) -> applyFromArray(   array( 'borders' => array( 'bottom' => array( 'style' => 'medium' ))));
        $this->sheet -> getStyleByColumnAndRow(0, 1, 1, 51) -> applyFromArray(   array( 'borders' => array( 'right' => array( 'style' => 'medium' ))));

        // układ strony
        $this->sheet -> getPageSetup() -> setOrientation(\PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
        $this->sheet -> getPageMargins() -> setTop(0.4) -> setLeft(0.3) -> setRight(0.3) -> setBottom(0.3);
        $this->sheet  -> getPageSetup() -> setFitToWidth(0);
    }

    private function setClassroomLessonForHour($col, $row, $classroom_id, $lessonHour_id) {	// znalezienie lekcji w sali dla podanej godziny i daty oraz wprowadzenie tego do arkusza
        $lessons = $this->lessonPlanRepo -> findClassroomLessonsForHour($classroom_id, $lessonHour_id, $this->dateView);
        if(count($lessons)==0) return;     // jeżeli w sali nie ma lekcji na wskazanej godzinie
        if(count($lessons)>1)  {    // jeżeli jest więcej niż jedna lekcja
            $this->sheet -> setCellValueByColumnAndRow($col, $row, ">1 lekcja");
            return;
        }
        // jeżeli w sali na wskazanej godzinie jest jedna lekcja
        foreach($lessons as $lesson) {
            $grades = "";
            foreach($lesson->group->grades as $groupGrade) {
                if(!$grades) $grades = $this->year-$groupGrade->grade->year_of_beginning;
                $grades .= $groupGrade->grade->symbol;
            }
            //foreach($lesson->group->teachers as $groupTeacher) {
            //    if($groupTeacher->date_start <= $this->dateView  &&  $groupTeacher->date_end >= $this->dateView)
            //        $grades .= " ".substr($groupTeacher->teacher->first_name, 0, 1).substr($groupTeacher->teacher->last_name, 0, 1);
            //}
            $this->sheet -> setCellValueByColumnAndRow($col, $row, $grades);
        }
    }    

    private function createFirstCollumn() {	// utworzenie i sformatowanie pierwszej kolumny w planie lekcji
        $sheet = $this->sheet;
        // wprowadzenie wartości do komórek oraz ich scalenie
        $this->sheet -> setCellValueByColumnAndRow(0, 2, 'poniedziałek')	-> mergeCells('A2:A11');
        $this->sheet -> setCellValueByColumnAndRow(0, 12, 'wtorek')		-> mergeCells('A12:A21');
        $this->sheet -> setCellValueByColumnAndRow(0, 22, 'środa')		-> mergeCells('A22:A31');
        $this->sheet -> setCellValueByColumnAndRow(0, 32, 'czwartek')		-> mergeCells('A32:A41');
        $this->sheet -> setCellValueByColumnAndRow(0, 42, 'piątek')		-> mergeCells('A42:A51');

        // wyrównanie komórek i obrócenie tekstu w nich
        $this->sheet -> getStyle("A2:A42") -> getAlignment() -> setTextRotation(90) -> setHorizontal('center') -> setVertical('center');
        // ustalenie wielkości i pogrubienia tekstu
        $this->sheet -> getStyle("A2:A42") -> getFont() -> setSize(18) -> setBold(true);
        $this->sheet -> getStyle("A1:A51") -> getBorders() -> getAllBorders() -> setBorderStyle('thin');
        $this->sheet -> getStyle("A1:A51") -> getBorders() -> getLeft() -> setBorderStyle('medium');

        // utworzenie drugiej kolumny
        for($d=1; $d<=5; $d++) for($g=1; $g<=10; $g++)		// dla każdego dnia dla wszystkich godzin
            $this->sheet -> setCellValueByColumnAndRow(1, $d*10+$g-9, $g);
        $this->sheet -> getStyle("B2:B51") -> getFont() -> setbold(true);
        $this->sheet -> getStyle("B2:B51") -> getAlignment() -> setHorizontal('center');
        $this->sheet -> getStyle("B1:B51") -> getBorders() -> getAllBorders() -> setBorderStyle('thin');
        $this->sheet -> getColumnDimension('A') -> setWidth(7);
        $this->sheet -> getColumnDimension('B') -> setAutoSize(true);
    }    
}