<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Repositories\GroupRepository;
use App\Repositories\GroupStudentRepository;
use App\Repositories\StudentNumberRepository;


class GroupStudentExportController extends Controller  {
    public function groups(GroupRepository $groupRepo, GroupStudentRepository $groupStudentRepo, StudentNumberRepository $studentNumberRepo)  {
        $dateView = session() -> get('dateView');
        $dateView = '2022-06-24';
        $grade = session()->get('gradeSelected');
        $subject = session()->get('subjectSelected');
        $level = session()->get('levelSelected');
        $teacher = session()->get('teacherSelected');
        $start = session() -> get('dateView');
        if(!empty(session() -> get('dateEnd'))) $end = session() -> get('dateEnd'); else $end=$start;
        $groups = $groupRepo -> getAllFilteredAndSorted($grade, $subject, $level, $start, $end, $teacher);

        //$schoolYear = $schoolYearRepo -> getSchoolYearIdForDate($dateView);

        Excel::create('lista_uczniow_w_grupach', function($excel) use ($groups, $dateView, $groupStudentRepo, $studentNumberRepo) {
            // Set the title
            $excel->setTitle('lista uczniów w grupach');
            // Chain the setters
            $excel->setCreator('Group Manager by Bartłomiej Trojnar')
                  ->setCompany('II Liceum Ogólnokształcące w Łańcucie');
            // Call them separately
            $excel->setDescription('Wygenerowano za pomocą aplikacji Group Manager');

            $excel->sheet('grupy', function($sheet) use ($groups, $dateView, $groupStudentRepo, $studentNumberRepo) {
                $this -> saveGroupComposition($sheet, $groups, $dateView, $groupStudentRepo, $studentNumberRepo);
            });
        })->export('xlsx');
    }

    private function saveGroupComposition($sheet, $groups, $dateView, $groupStudentRepo, $studentNumberRepo) {
        $col = 0; $row = 1;
        foreach($groups as $group) {
            $sheet -> setCellValueByColumnAndRow($col, $row, $group->subject->name);
            $sheet -> getStyleByColumnAndRow($col, $row) -> getFont() -> setbold(true) -> setSize(14);
            
            $grades = "";
            foreach($group->grades as $grade)   $grades .= $grade->grade->symbol;
            $sheet -> setCellValueByColumnAndRow($col+1, $row, $grades);
            $sheet -> getStyleByColumnAndRow($col+1, $row) -> getFont() -> setbold(true) -> setSize(12);

            $teachers = "";
            foreach($group->teachers as $teacher)   {
                if($teacher->start <= $dateView && $teacher->end >= $dateView)
                $teachers .= $teacher->teacher->first_name." ".$teacher->teacher->last_name;
            }
            $sheet -> setCellValueByColumnAndRow($col+2, $row, $teachers);
            $sheet -> getStyleByColumnAndRow($col+2, $row) -> getFont() -> setbold(true) -> setSize(12);
            $sheet -> getStyleByColumnAndRow($col, $row, $col+2, $row) -> applyFromArray(   array( 'borders' => array( 'bottom' => array( 'style' => 'medium' ))));

            $row = $this->groupStudents($sheet, $col, $row, $group, $dateView, $groupStudentRepo, $studentNumberRepo);

            $row += 2;
        }
    }

    private function groupStudents($sheet, $col, $row, $group, $dateView, $groupStudentRepo, $studentNumberRepo) {
        $grade_id = session()->get('gradeSelected');
        $lp = 0;
        $groupStudents = $groupStudentRepo -> getGroupStudents($group->id, $dateView);
        foreach($groupStudents as $groupStudent) {
            $col = 1;
            $student = $groupStudent->student;
            // wyświetlenie danych ucznia, liczba porządkowa
            $sheet -> setCellValueByColumnAndRow($col, ++$row, ++$lp);

            // klasa i numer ucznia
            $grade = $this -> getStudentGrade($student, $grade_id, $studentNumberRepo);
            $sheet -> setCellValueByColumnAndRow(++$col, $row, $grade);

            // nazwisko i imię
            $studentName = $student->last_name." ".$student->first_name;
            $sheet -> setCellValueByColumnAndRow($col+1, $row, $studentName);
            // daty przynależności do grupy
            $sheet -> setCellValueByColumnAndRow($col+2, $row, $groupStudent->start);
            $sheet -> setCellValueByColumnAndRow($col+3, $row, $groupStudent->end);

            if( ($groupStudent->start > $dateView) || ($groupStudent->end < $dateView)) {
                $sheet -> getStyleByColumnAndRow($col, $row, $col+3, $row) -> applyFromArray(   array(  'fill' => array( 'type' => \PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'ffeeee')  )    ));
                $sheet -> getStyleByColumnAndRow($col, $row, $col+3, $row) -> applyFromArray(   array(  'font' => array(
                    'underline' => true,
                )));
            }
        }
        return $row;
    }

    private function getStudentGrade($student, $grade_id, $studentNumberRepo) {
        $grade = "";
        foreach($student->grades as $studentGrade) {
            if($studentGrade->grade_id != $grade_id) continue;
            $grade .= $studentGrade->grade->symbol;
            $studentNumbers = $studentNumberRepo -> getStudentNumbers($student->id);
            foreach($studentNumbers as $number) {
                if($number->grade_id == $studentGrade->grade_id)  $grade.=" ".$number->number;
            }
        }
        return $grade;
    }
}
