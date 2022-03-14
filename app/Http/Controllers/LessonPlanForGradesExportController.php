<?php
// ------------------------ (C) mgr inż. Bartłomiej Trojnar; 15.10.2021 ------------------------ //
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

use App\Models\Grade;
use App\Models\LessonPlan;
use App\Repositories\GradeRepository;
use App\Repositories\LessonPlanRepository;

class LessonPlanForGradesExportController extends Controller
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

            $this -> createGradeSheets($excel);
        })->export('xlsx');
    }

    private function createGradeSheets($excel) {
        $excel->sheet('klasy', function($sheet) {
            $this->sheet = $sheet;
            $gradeRepo = new GradeRepository(new Grade);
            // wyszukanie klas w odnalezionym roku
            $grades = $gradeRepo -> getGradesInYear($this->year);
            $lp=0;
            foreach($grades as $grade) {    // utworzenie tabelki z lekcjami dla każdej klasy
                $gradeName = ($this->year - $grade->year_of_beginning).$grade->symbol;
                $row=3+$lp*12; $col=2;
                for($lessonHour=1; $lessonHour<=75; $lessonHour++, $row++) {
                    if($row>11+$lp*12) {$row=2+$lp*12; $col++;}
                    $this -> setGradeLessonForHour($col, $row, $grade->id, $lessonHour);
                    if($lessonHour % 15 == 10) $lessonHour += 5;
                }    
                $this -> sheetHeaders($gradeName, $lp);    // formatowanie arkusza
                $lp++;
            }
            $this -> sheetFormat(); 
        });
    }

    function sheetHeaders($gradeName, $lp) {
        // nagłówek - nazwa klasy
        $this->sheet -> setCellValueByColumnAndRow(0, 1+$lp*12, $gradeName);
        $rowStart = 1+$lp*12;
        $this->sheet -> mergeCells('A'.$rowStart.':G'.$rowStart);
        $this->sheet -> getStyleByColumnAndRow(0, $rowStart) -> getFont() -> setbold(true) -> setSize(16);
        $this->sheet -> getStyleByColumnAndRow(0, $rowStart, 6, $rowStart+1) -> getAlignment() -> setHorizontal('center');
        $this->sheet -> getStyleByColumnAndRow(0, $rowStart) -> getFill() -> setFillType(\PHPExcel_Style_Fill::FILL_SOLID) -> getStartColor() -> setRGB('AADDAA');

        // zawijanie tekstu
        $this->sheet -> getStyleByColumnAndRow(2, $rowStart+2, 6, $rowStart+10) -> getAlignment() -> setWrapText(true) -> setVertical('top');
 
        $this->sheet -> setCellValueByColumnAndRow(2, 1+$rowStart, 'poniedziałek');
        $this->sheet -> setCellValueByColumnAndRow(3, 1+$rowStart, 'wtorek');
        $this->sheet -> setCellValueByColumnAndRow(4, 1+$rowStart, 'środa');
        $this->sheet -> setCellValueByColumnAndRow(5, 1+$rowStart, 'czwartek');
        $this->sheet -> setCellValueByColumnAndRow(6, 1+$rowStart, 'piątek');
        $this->sheet -> getStyleByColumnAndRow(2, $rowStart+1, 6, $rowStart+1) -> getFont() -> setbold(true) -> setSize(14);

        $this->sheet -> setCellValueByColumnAndRow(0, 2+$rowStart, '1');
        $this->sheet -> setCellValueByColumnAndRow(0, 3+$rowStart, '2');
        $this->sheet -> setCellValueByColumnAndRow(0, 4+$rowStart, '3');
        $this->sheet -> setCellValueByColumnAndRow(0, 5+$rowStart, '4');
        $this->sheet -> setCellValueByColumnAndRow(0, 6+$rowStart, '5');
        $this->sheet -> setCellValueByColumnAndRow(0, 7+$rowStart, '6');
        $this->sheet -> setCellValueByColumnAndRow(0, 8+$rowStart, '7');
        $this->sheet -> setCellValueByColumnAndRow(0, 9+$rowStart, '8');
        $this->sheet -> setCellValueByColumnAndRow(0, 10+$rowStart, '9');

        $this->sheet -> setCellValueByColumnAndRow(1, 2+$rowStart, '7:10-7:55');
        $this->sheet -> setCellValueByColumnAndRow(1, 3+$rowStart, '8:00-8:45');
        $this->sheet -> setCellValueByColumnAndRow(1, 4+$rowStart, '8:50-9:35');
        $this->sheet -> setCellValueByColumnAndRow(1, 5+$rowStart, '9:45-10:30');
        $this->sheet -> setCellValueByColumnAndRow(1, 6+$rowStart, '10:50-11:35');
        $this->sheet -> setCellValueByColumnAndRow(1, 7+$rowStart, '11:40-12:25');
        $this->sheet -> setCellValueByColumnAndRow(1, 8+$rowStart, '12:35-13:20');
        $this->sheet -> setCellValueByColumnAndRow(1, 9+$rowStart, '13:25-14:10');
        $this->sheet -> setCellValueByColumnAndRow(1, 10+$rowStart, '14:15-15:00');

        $this->sheet -> getStyleByColumnAndRow(0, $rowStart+2, 0, $rowStart+10) -> getFont() -> setbold(true) -> setSize(14);
        $this->sheet -> getStyleByColumnAndRow(0, $rowStart+2, 1, $rowStart+10) -> getAlignment() -> setVertical('center');
        $this->sheet -> getStyleByColumnAndRow(2, $rowStart+2, 6, $rowStart+10) -> getFont() -> setSize(14);

        // obramowanie
        $this->sheet -> getStyleByColumnAndRow(0, $rowStart, 6, $rowStart+10) -> applyFromArray( array( 'borders' => array( 'allborders' => array(
            'style' => 'thin',
            'color' => array('rgb' => '000000')
        ))));
        $this->sheet -> getStyleByColumnAndRow(0, $rowStart, 6, $rowStart+10) -> applyFromArray(   array( 'borders' => array( 'top' => array( 'style' => 'medium' ))));
        $this->sheet -> getStyleByColumnAndRow(0, $rowStart, 6, $rowStart+10) -> applyFromArray(   array( 'borders' => array( 'right' => array( 'style' => 'medium' ))));
        $this->sheet -> getStyleByColumnAndRow(0, $rowStart, 6, $rowStart+10) -> applyFromArray(   array( 'borders' => array( 'bottom' => array( 'style' => 'medium' ))));
        $this->sheet -> getStyleByColumnAndRow(0, $rowStart, 6, $rowStart+10) -> applyFromArray(   array( 'borders' => array( 'left' => array( 'style' => 'medium' ))));

        $this->sheet -> getStyleByColumnAndRow(0, $rowStart, 6, $rowStart+1) -> applyFromArray(   array( 'borders' => array( 'bottom' => array( 'style' => 'medium' ))));
        $this->sheet -> getStyleByColumnAndRow(0, $rowStart+1, 1, $rowStart+10) -> applyFromArray(   array( 'borders' => array( 'right' => array( 'style' => 'medium' ))));
    }

    function sheetFormat() {
        // szerokość kolumn
        $this->sheet -> setWidth(chr(66), 12);
        $this->sheet -> setWidth(chr(67), 40);
        $this->sheet -> setWidth(chr(68), 40);
        $this->sheet -> setWidth(chr(69), 40);
        $this->sheet -> setWidth(chr(70), 40);
        $this->sheet -> setWidth(chr(71), 40);
        // układ strony
        $this->sheet -> getPageSetup() -> setOrientation(\PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
        $this->sheet -> getPageMargins() -> setTop(0.4) -> setLeft(0.4) -> setRight(0.4);
        $this->sheet -> getPageSetup() -> setFitToHeight(0);
    }

    function setGradeLessonForHour($col, $row, $grade_id, $lessonHour_id) {	// znalezienie lekcji klasy dla podanej godziny i daty oraz wprowadzenie tego do arkusza
        $lessons = $this->lessonPlanRepo -> findGradeLessonsForHour($grade_id, $lessonHour_id, $this->dateView);
        if(count($lessons)==0) return;     // jeżeli klasa nie ma lekcji na wskazanej godzinie
        $description = new \PHPExcel_RichText();
        $n=0;   // licznik lekcji na godzinie
        foreach($lessons as $lesson) {
            if($n) $part = $description -> createTextRun("\n");
            $n++;
            // utworzenie opisu lekcji - krótka nazwa przedmiotu oraz oznaczenie grupy
            $subject = $lesson->group->subject->short_name;
            if( $lesson->group->comments ) $subject .= " {".$lesson->group->comments."}";
            $part = $description -> createTextRun($subject);
            if($lesson->group->level == 'rozszerzony')  $part -> getFont() -> setBold(true);

            // dopisanie klas w grupie (jeżeli jest więcej niż jedna klasa)
            $groupGrades = $lesson->group->grades;
            if(count($groupGrades)>1) {
                $gradesDesc = '[';
                foreach($groupGrades as $groupGrade)    $gradesDesc .= $groupGrade->grade->symbol;
                $gradesDesc .= "]";
                $part = $description -> createTextRun($gradesDesc);
                $part -> getFont() -> setSize(8);
            }

            // dopisanie inicjałów nauczyciela
            $groupTeachers = $lesson->group->teachers;
            $teacher = " [";
            foreach($groupTeachers as $groupTeacher) {
                if($groupTeacher->date_start<=$this->dateView && $groupTeacher->date_end>=$this->dateView)    $teacher .= $groupTeacher->teacher->short;
            }
            $teacher .= "] ";
            //$part = $description -> createTextRun($teacher);

            // dopisanie nazwy sali
            if($lesson->classroom_id)   $part = $description -> createTextRun("(".$lesson->classroom->name .")");
        }
        $this->sheet -> setCellValueByColumnAndRow($col, $row, $description);
    }    
}