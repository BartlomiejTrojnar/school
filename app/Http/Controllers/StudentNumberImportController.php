<?php
// ------------------------ (C) mgr inż. Bartłomiej Trojnar; 24.06.2023 ------------------------ //
namespace App\Http\Controllers;

use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use App\Models\StudentNumber;
use App\Repositories\GradeRepository;
use App\Repositories\StudentRepository;
use App\Repositories\SchoolYearRepository;
use Illuminate\Http\Request;
//use mysqli;

class StudentNumberImportController extends Controller {
    public function importMenu(SchoolYearRepository $schoolYearRepo, GradeRepository $gradeRepo) {
        $schoolYearSelected = session()->get('schoolYearSelected');
        $schoolYears = $schoolYearRepo -> getAllSorted();
        $schoolYearSF = view('schoolYear.selectField', ["schoolYears"=>$schoolYears, "schoolYearSelected"=>$schoolYearSelected, "name"=>"school_year_id"]);
        $year = $schoolYearSelected+1900;
        $grades = $gradeRepo -> getAllSorted();
        $gradeSelected = session()->get('gradeSelected');
        $gradeSF = view('grade.selectField', ["grades"=>$grades, "gradeSelected"=>$gradeSelected, "name"=>"grade_id", "year"=>$year]);

        Excel::load('C:\dane\nauczyciele\numery_uczniow\importujNumery.xlsx', function($reader) {
            $this->sheets = $reader->get();
        });

        return view('studentNumber.importMenu', ['schoolYearSF'=>$schoolYearSF, 'gradeSF'=>$gradeSF, 'sheets'=>$this->sheets]);
    }

    public function import(Request $request, StudentRepository $studentRepo) {
        $this->studentRepo = $studentRepo;
        $this->grade = $request->grade_id;
        $this->schoolYear = $request->school_year_id;
        $sheet_name = $request->sheet_name;

        $this->wynik = '<section style="background: #bbb;"><p>Trwa import dla klasy '.$this->grade.' i roku szkolnego '.$this->schoolYear.'...</p>';
        $this->wynik.= '<ol>';
        Excel::selectSheets($sheet_name)->load('C:\dane\nauczyciele\numery_uczniow\importujNumery.xlsx', function($reader) {
            $students = $reader->get();
            foreach($students as $student) {
                $student_id = $this -> findStudent($student);
                if($student_id)
                    // sprawdzenie czy numer ucznia w bazie danych się zgadza
                    $this->wynik.= $this -> checkStudentNumber($student_id, $student);
            }
        });
        $this->wynik.= '</ol><a style="color: #009; border: 2px solid #009; padding: 5px; border-radius: 5px; background: #88f; position: relative; left: 750px; bottom: 225px;" href="'.url("numery_ucznia/importMenu").'">wróć do importu</a></section>';
        return view('studentNumber.import', ['result'=>$this->wynik]);
    }

    private function findStudent($student) {
        // znalezienie ucznia w bazie danych
        // sprawdzenie czy jest osoba o podanym nazwisku i imionach
        $student_id = $this->studentRepo -> findStudentIdByGradeAndName($this->grade, $student['nazwisko'], $student['imie'], $student['drugie_imie']);
        if($student_id < 0) {
            $this->wynik.= '<li style="color: blue; background: #aaf;">Znaleziono kilku uczniów z podanym nazwiskiem '.$student['nazwisko'].' i imionami '.$student['imie'].' '.$student['drugie_imie'].'.</li>';
            return 0;
        }
        if($student_id == 0) {
            $this->wynik.= '<li>Klasa: '.$this->grade.'</li>';
            $this->wynik.= '<li style="color: #f60;">Nie znaleziono ucznia '.$student['nazwisko'].' '.$student['imie'].' '.$student['drugie_imie'].'.</li>';
            return 0;
        }    
        return $student_id;
    }

    private function checkStudentNumber($student_id, $student) {
        $numbers = StudentNumber::where('student_id', '=', $student_id) -> where('grade_id', '=', $this->grade)
            -> where('school_year_id', '=', $this->schoolYear) -> get();
        foreach($numbers as $number) {
            if($number['number'] == $student['nr']) {
                $wynik = '<li style="color: #444;">Dla ucznia <strong>'.$student['nazwisko'].' '.$student['imie'].' '.$student['drugie_imie'].'</strong> ';
                $wynik.= 'numer w bazie zgadza się z importowanym ['.$number['number'].' = '.$student['nr'].'].</li>';
                return $wynik;
            }
            else {
                $wynik = '<li class="danger">Dla ucznia <strong>'.$student['nazwisko'].' '.$student['imie'].' '.$student['drugie_imie'].'</strong> ';
                $wynik.= 'numer w bazie '.$number['number'].' nie zgadza się z importowanym '.$student['nr'].'.</li>';
                $wynik.= '<li style="background: red;">Dopisz odpowiednią funkcję dla tego przypadku</li>';
                return $wynik;
            }
        }
        if(count($numbers)==0) $this->addNumberForStudent($student_id, $student);
        echo '<p style="background: #f66;">Brak numeru</p>';
        return;
        // $grade_id = $grade[0]['id'];

        // $studentGrade = StudentGrade::where('student_id', '=', $student_id) -> where('grade_id', '=', $grade_id) -> get();
        // if(count($studentGrade)==0) {
            // printf('<p>BRAK KLAS dla ucznia. Dodaję.</p>');
            // $this -> addStudentGrade($student_id, $grade_id, $od, $do);
            // return;
        // }
        // if(count($studentGrade)!=1) { echo '<p>Znaleziono NIEWŁAŚCIWĄ ilość klas ucznia.</p>'; return; }
        // $od = substr($od, 0, 10);
        // $do = substr($do, 0, 10);
        // if($studentGrade[0]['start'] != $od) { printf('<p>Data początkowa ucznia w klasie: w bazie %s, w pliku %s.</p>', $studentGrade[0]['start'], $od); return; }
        // if($studentGrade[0]['end'] != $do) { printf('<p>Data końcowa ucznia w klasie: w bazie %s, w pliku %s.</p>', $studentGrade[0]['end'], $do); return; }
        // if($studentGrade[0]['confirmation_start'] != 1) { printf('<p>Data początkowa ucznia w klasie NIEPOTWIERDZONA.</p>', $studentGrade[0]['end'], $do); return; }
        // if($studentGrade[0]['end'] != '2026-04-24' && $studentGrade[0]['confirmation_end'] != 1) { printf('<p>Data końcowa %s ucznia w klasie NIEPOTWIERDZONA.</p>', $studentGrade[0]['end']); return; }
        // if($studentGrade[0]['end'] == '2026-04-24' && $studentGrade[0]['confirmation_end'] != 0) { printf('<p>Data końcowa %s ucznia w klasie POTWIERDZONA.</p>', $studentGrade[0]['end']); return; }
    }


    private function addNumberForStudent($student_id, $student) {
        $newStudentNumber = new StudentNumber;
        $newStudentNumber->student_id = $student_id;
        $newStudentNumber->grade_id = $this->grade;
        $newStudentNumber->school_year_id = $this->schoolYear;
        $newStudentNumber->number = $student['nr'];
        $newStudentNumber->confirmation_number = 0;
        printf('<p style="background: grey;">');
        print_r($newStudentNumber);
        printf('</p>');
        $newStudentNumber->save();
    }
}