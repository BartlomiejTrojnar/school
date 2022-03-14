<?php
// ------------------------ (C) mgr inż. Bartłomiej Trojnar; 17.12.2021 ------------------------ //
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

use App\Models\Grade;
use App\Models\LessonPlan;
use App\Repositories\GradeRepository;
use App\Repositories\LessonPlanRepository;

class LessonPlanForGradesExportControllerOldVersion extends Controller
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

            $excel->sheet('klasy', function($sheet) {
                $this->sheet = $sheet;
                $this -> createGradeSheet();
            });
        })->export('xlsx');
    }

    private function createGradeSheet() {
        // utworzenie pierwszej kolumny
        $this -> createFirstCollumn();

        // wyszukanie klas w odnalezionym roku
        $gradeRepo = new GradeRepository(new Grade);
        $grades = $gradeRepo -> getGradesInYear($this->year);
        $row=1; $col=2;
        foreach($grades as $grade) {
            // wprowadzenie nazwy klasy oraz sformatowanie komórki
            $studyYear = $this->year - $grade->year_of_beginning;
            $this->sheet -> setCellValueByColumnAndRow($col, $row, $studyYear.$grade->symbol);
            $this->sheet -> getStyleByColumnAndRow($col, $row) -> getFont() -> setbold(true) -> setSize(14);

            // wprowadzenie i sformatowanie komórek dla każdej godziny w tygodniu
            $row=2;
            for($lessonHour=1; $lessonHour<=75; $lessonHour++, $row++) {
                $this -> setGradeLessonForHour($col, $row, $grade->id, $lessonHour);
                if($lessonHour % 15 == 10) $lessonHour += 5;
            }
            $this->sheet -> getColumnDimensionByColumn($col) -> setAutoSize(true);
            $this->sheet -> getColumnDimensionByColumn($col+1) -> setAutoSize(true);
            $col+=2; $row=1;
        }

        $this -> sheetFormat($col-1);
    }

    private function sheetFormat($col) {
        $this->sheet -> setWidth(chr(65), 6);
        $this->sheet -> getStyleByColumnAndRow(0, 1, $col, 51) -> getAlignment() -> setVertical('center') -> setHorizontal('center') -> setWrapText(false);

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
        $this->sheet -> getStyleByColumnAndRow(0, 1, 1, 51) -> applyFromArray(   array( 'borders' => array( 'bottom' => array( 'style' => 'medium' ))));

        // układ strony
        $this->sheet -> getPageSetup() -> setOrientation(\PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
        $this->sheet -> getPageMargins() -> setTop(0.6) -> setLeft(0.2) -> setRight(0.2) -> setBottom(0.2);
        $this->sheet -> getPageSetup() -> setFitToWidth(0);
        
        // nagłówek strony
        $this->sheet -> getHeaderFooter() -> setOddHeader('plan lekcji od '.$this->dateView);

        // powtarzane kolumny na wydruku
        $this->sheet -> getPageSetup() -> setColumnsToRepeatAtLeftByStartAndEnd('A', 'B');
    }

    private function setGradeLessonForHour($col, $row, $grade_id, $lessonHour_id) {	// znalezienie lekcji klasy dla podanej godziny i daty oraz wprowadzenie tego do arkusza
        $lessons = $this->lessonPlanRepo -> findGradeLessonsForHour($grade_id, $lessonHour_id, $this->dateView);
        if(count($lessons)==0) return;     // jeżeli klasa nie ma lekcji na wskazanej godzinie
        $description = new \PHPExcel_RichText();
        $classrooms = "";
        $n=0; $background = false;
        foreach($lessons as $lesson) {
            if($n) $part = $description -> createTextRun("/");
            $n++;
            // utworzenie opisu lekcji - krótka nazwa przedmiotu oraz oznaczenie grupy
            if( date('Y-m-d', strtotime('+7 day', strtotime($lesson->date_start))) >=$this->dateView) $background=true;
            $subject = $lesson->group->subject->short_name;
            if($lesson->group->comments) $subject .= " {".$lesson->group->comments."}";

            $part = $description -> createTextRun($subject);
            if($lesson->group->level == 'rozszerzony')  $part -> getFont() -> setBold(true);

            // dopisanie klas w grupie (jeżeli jest więcej niż jedna klasa)
            $groupGrades = $lesson->group->grades;
            if(count($groupGrades)>1) {
                $gradesDesc = " [";
                foreach($groupGrades as $groupGrade)    $gradesDesc .= $groupGrade->grade->symbol;
                $gradesDesc .= "]";
                $part = $description -> createTextRun($gradesDesc);
                $part -> getFont() -> setSize(8);
            }

            // dopisanie nazwy sali
            if($lesson->classroom_id) {
                $classrooms .= $lesson->classroom->name ."/";
            }
        }
        if($classrooms) $classrooms = substr($classrooms, 0, -1);
        $this->sheet -> setCellValueByColumnAndRow($col, $row, $description);
        $this->sheet -> setCellValueByColumnAndRow($col+1, $row, $classrooms);
        if($background) $this->sheet -> getStyleByColumnAndRow($col, $row, $col+1, $row) -> applyFromArray(array( 'fill' => array(
                'type'  => \PHPExcel_Style_Fill::FILL_SOLID,    'color' => array('rgb' => 'eeeeee') ) ));
    }    

    private function createFirstCollumn() {	// utworzenie i sformatowanie pierwszej kolumny w planie lekcji
        $sheet = $this->sheet;
        // wprowadzenie wartości do komórek oraz ich scalenie
        $this->sheet -> setCellValueByColumnAndRow(0, 2, 'poniedziałek')-> mergeCells('A2:A11');
        $this->sheet -> setCellValueByColumnAndRow(0, 12, 'wtorek')		-> mergeCells('A12:A21');
        $this->sheet -> setCellValueByColumnAndRow(0, 22, 'środa')		-> mergeCells('A22:A31');
        $this->sheet -> setCellValueByColumnAndRow(0, 32, 'czwartek')	-> mergeCells('A32:A41');
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