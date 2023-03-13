<?php
// ------------------------ (C) mgr inż. Bartłomiej Trojnar; 31.05.2022 ------------------------ //
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use App\Models\BookOfStudent;
use App\Models\Grade;
use App\Models\Student;
use App\Models\StudentGrade;
use App\Models\StudentHistory;
use App\Repositories\BookOfStudentRepository;
use App\Repositories\SchoolRepository;
use App\Repositories\StudentRepository;
use mysqli;

class StudentImportController extends Controller {
    public function import(StudentRepository $studentRepo, SchoolRepository $schoolRepo, BookOfStudentRepository $bookRepo, StudentHistory $studentHistory) {
        $this->studentRepo = $studentRepo;
        $this->schoolRepo = $schoolRepo;
        $this->bookRepo = $bookRepo;
        $this->studentHistory = $studentHistory;

        echo '<p><a href="'.route('uczen.index').'">uczniowie</a></p>';

        Excel::selectSheets('Uczniowie')->load('C:\dane\nauczyciele\ksiegauczniow\KsiegaUczniow2LO.xlsx', function($reader) {
            $students = $reader->get();
            foreach($students as $student) {
                printf('<p>Sprawdzam ucznia %s %s %s.</p>', $student['imie'], $student['imie2'], $student['nazwisko']);
                $student_id = $this -> checkStudent($student);
                if($student_id) {
                    // printf('<p><a href="%s">przejdź do %s %s %s</a>.</p>', route('uczen.show', $student_id), $student['imie'], $student['imie2'], $student['nazwisko']);
                    $this -> checkBookOfStudent($student_id, $student['szkola'], $student['numer_ksiegi']);
                    $this -> checkStudentHistory($student_id, $student['data_przyjecia'], $student['data_opuszczenia'], $student['powod_opuszczenia']);
                    $this -> checkStudentGrade($student_id, $student['oddzial'], $student['od'], $student['do']);
                }
                echo '<br />**********<br />';
            }
        });
    }

    private function checkStudent($student) {
        // sprawdzenie czy w bazie danych istnieje osoba o podanym peselu
        $student_id = $this->studentRepo -> findStudentIdByPesel($student['pesel']);
        if($student_id < 0) { printf('<p style="background: grey;">Znaleziono kilku uczniów z peselem %s.</p>', $student['pesel']); exit; }
        if($student_id == 0) {
            printf('<p style="color: red; background: orange;">Nie znaleziono ucznia %s z podanym peselem %s.</p>', $student['nazwisko'], $student['pesel']);
            // sprawdzenie czy jest osoba o podanym nazwisku i imionach
            $student_id = $this->studentRepo -> findStudentIdByName($student['nazwisko'], $student['imie'], $student['imie2']);
            if($student_id < 0) { printf('<p style="color: blue; background: #aaf;">Znaleziono kilku uczniów z podanym nazwiskiem %s i imionami %s %s.</p>', $student['nazwisko'], $student['imie'], $student['imie2']); exit; }
            if($student_id == 0) {
                printf('<p style="color: #f60;">Nie znaleziono ucznia z podanym nazwiskiem %s i imionami %s %s. DODAJĘ</p>', $student['nazwisko'], $student['imie'], $student['imie2']);
                return $this -> addStudent($student);
            }    
        }
        else {
            $wynik = $this->studentRepo -> checkStudentWithNames($student_id, $student['nazwisko'], $student['imie'], $student['imie2']);
            if(!$wynik) {
                printf('<p style="color: #141; background: #d7d;">Nie zgadzają się imiona lub nazwisko.</p>', $student['nazwisko'], $student['imie'], $student['imie2']);
                printf('<p style="color: #141; background: #d7d;">PESEL: %s.</p>', $student['pesel']);
                printf('<p><a href="%s">przejdź do %s %s %s</a>.</p>', route('uczen.show', $student_id), $student['imie'], $student['imie2'], $student['nazwisko']);
                exit;
            }
        }
        return $student_id;
    }

    private function addStudent($student) {
        $newStudent = new Student;
        $newStudent->first_name = $student['imie'];
        $newStudent->second_name = $student['imie2'];
        $newStudent->last_name = $student['nazwisko'];
        $newStudent->family_name = $student['rodowe'];
        $newStudent->sex = $student['plec'];
        $newStudent->PESEL = $student['pesel'];
        $newStudent->place_of_birth = $student['miejsce_urodzenia'];
        $newStudent->save();
        return $newStudent->id;
    }

    private function checkBookOfStudent($student_id, $school_name, $number) {
        // pobranie identyfikatora szkoły
        $school_id = $this->schoolRepo -> findSchoolIdByName($school_name);
        // sprawdzenie czy w bazie danych uczeń ma numer księgi
        $book_number = $this->bookRepo -> checkStudentNumber($student_id, $school_id);
        if($book_number < 0) { printf('<p style="color: #aaf; background: #55a;">Znaleziono kilka numerów w księdze ucznia dla ucznia id=%d.</p>', $student_id); exit; }
        if($book_number == 0) $this -> addBookOfStudent($school_id, $student_id, $number);
        else if($book_number != $number) {
            printf('<p style="color: blue;">Podany numer(%d) jest inny niż numer(%d) w bazie danych dla ucznia id=%d.</p>', $number, $book_number, $student_id); exit;
        }
    }

    private function addBookOfStudent($school_id, $student_id, $number) {
        printf('<p style="background: #a44;">Dodaję numer księgi</p>');
        $newBookOfStudent = new BookOfStudent;
        $newBookOfStudent->school_id    = $school_id;
        $newBookOfStudent->student_id   = $student_id;
        $newBookOfStudent->number       = $number;
        $newBookOfStudent->save();
    }

    private function checkStudentHistory($student_id, $przyjecie, $opuszczenie, $powod_opuszczenia) {
        $data_przyjecia = substr($przyjecie,6,4) .'-'. substr($przyjecie,3,2) .'-'. substr($przyjecie,0,2);
        // sprawdzenie czy w bazie danych uczeń ma odpowiedni wpis w swojej historii
        $studentHistory = StudentHistory::where('student_id', '=', $student_id) -> where('date', '=', $data_przyjecia) -> get();
        if(count($studentHistory) && $studentHistory[0]['event'] == 'przyjęto do II LO') ;
        else if(count($studentHistory)) {
            printf('<p style="color: blue;">Podany wpis historii ucznia (%s) jest inna niż "przyjęto do II LO" dla ucznia id=%d.</p>', $studentHistory[0]['event'], $student_id);
            exit;
        }
        else 
        // wprowadzenie historii ucznia
        $this -> addStudentHistoryDateOfAdmission($student_id, $data_przyjecia);

        // sprawdzenie według daty opuszczenia szkoły
        if(!$opuszczenie) return;
        $data_opuszczenia = substr($opuszczenie,6,4) .'-'. substr($opuszczenie,3,2) .'-'. substr($opuszczenie,0,2);
        $studentHistory = StudentHistory::where('student_id', '=', $student_id) -> where('date', '=', $data_opuszczenia) -> get();
        if(count($studentHistory) && $studentHistory[0]['event'] == $powod_opuszczenia) return;
        if(count($studentHistory) && $studentHistory[0]['event'] == 'ukończenie szkoły') return;
        if(count($studentHistory)) {
            printf('<p style="background: #aaf; color: blue;">Podany wpis historii ucznia (%s) jest inna niż "ukończenie szkoły" dla ucznia id=%d.</p>', $studentHistory[0]['event'], $student_id);
            printf('<p style="background: #aaf; color: blue;">Powód opuszczenia: %s.</p>', $powod_opuszczenia);
            exit;
        }
        // wprowadzenie historii ucznia
        $this -> addStudentHistoryDateOfDeparture($student_id, $data_opuszczenia, $powod_opuszczenia);
    }

    private function addStudentHistoryDateOfAdmission($student_id, $data_przyjecia) {
        $newStudentHistory = new StudentHistory;
        $newStudentHistory->student_id = $student_id;
        $newStudentHistory->date = $data_przyjecia;
        $newStudentHistory->event = 'przyjęto do II LO';
        $newStudentHistory->confirmation_date = 1;
        $newStudentHistory->confirmation_event = 1;
        $newStudentHistory->save();
    }

    private function addStudentHistoryDateOfDeparture($student_id, $data_opuszczenia, $powod_opuszczenia) {
        if($powod_opuszczenia==NULL) $powod_opuszczenia = 'rezygnacja z nauki(?)';
        $newStudentHistory = new StudentHistory;
        $newStudentHistory->student_id = $student_id;
        $newStudentHistory->date = $data_opuszczenia;
        $newStudentHistory->event = $powod_opuszczenia;
        $newStudentHistory->confirmation_date = 1;
        $newStudentHistory->confirmation_event = 1;
        if($powod_opuszczenia=='rezygnacja z nauki(?)')     $newStudentHistory->confirmation_event=0;
        $newStudentHistory->save();
    }

    private function checkStudentGrade($student_id, $grade_name, $od, $do) {
        printf('Sprawdzam klasę dla %s, %s, %s - %s', $student_id, $grade_name, $od, $do);
        if($grade_name == NULL) return;
        //$year_of_beginning = date('Y') - intval(substr($grade_name, 0, 1)/2+0.5);
        $year_of_beginning = date('Y') - intval(substr($grade_name, 0, 1)-1);
        $symbol = substr($grade_name, 1);
        $grade = Grade::where('year_of_beginning', '=', $year_of_beginning) -> where('symbol', '=', $symbol) -> get();
        $grade_id = $grade[0]['id'];

        $studentGrade = StudentGrade::where('student_id', '=', $student_id) -> where('grade_id', '=', $grade_id) -> get();
        if(count($studentGrade)==0) {
            printf('<p>BRAK KLAS dla ucznia. Dodaję.</p>');
            $this -> addStudentGrade($student_id, $grade_id, $od, $do);
            return;
        }
        if(count($studentGrade)!=1) { echo '<p>Znaleziono NIEWŁAŚCIWĄ ilość klas ucznia.</p>'; return; }
        $od = substr($od, 0, 10);
        $do = substr($do, 0, 10);
        if($studentGrade[0]['start'] != $od) { printf('<p>Data początkowa ucznia w klasie: w bazie %s, w pliku %s.</p>', $studentGrade[0]['start'], $od); return; }
        if($studentGrade[0]['end'] != $do) { printf('<p>Data końcowa ucznia w klasie: w bazie %s, w pliku %s.</p>', $studentGrade[0]['end'], $do); return; }
        if($studentGrade[0]['confirmation_start'] != 1) { printf('<p>Data początkowa ucznia w klasie NIEPOTWIERDZONA.</p>', $studentGrade[0]['end'], $do); return; }
        if($studentGrade[0]['end'] != '2026-04-24' && $studentGrade[0]['confirmation_end'] != 1) { printf('<p>Data końcowa %s ucznia w klasie NIEPOTWIERDZONA.</p>', $studentGrade[0]['end']); return; }
        if($studentGrade[0]['end'] == '2026-04-24' && $studentGrade[0]['confirmation_end'] != 0) { printf('<p>Data końcowa %s ucznia w klasie POTWIERDZONA.</p>', $studentGrade[0]['end']); return; }
    }

    private function addStudentGrade($student_id, $grade_id, $start, $end) {
        $newStudentGrade = new StudentGrade;
        $newStudentGrade->student_id = $student_id;
        $newStudentGrade->grade_id = $grade_id;
        $newStudentGrade->start = $start;
        $newStudentGrade->confirmation_start = 1;
        $newStudentGrade->end = $end;
        $newStudentGrade->confirmation_end = 0;
        //print_r($newStudentGrade);
        $newStudentGrade->save();
    }
}