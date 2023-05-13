<?php
// ------------------------ (C) mgr inż. Bartłomiej Trojnar; 11.05.2023 ------------------------ //
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
        $grades = $gradeRepo -> getAllSorted();
        $gradeSelected = session()->get('gradeSelected');
        $gradeSF = view('grade.selectField', ["grades"=>$grades, "gradeSelected"=>$gradeSelected, "name"=>"grade_id"]);

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

        echo '<section style="background: #bbb;">';
        printf('<p>Trwa import dla klasy %s i roku szkolnego %s...</p>', $this->grade, $this->schoolYear);

        Excel::selectSheets($sheet_name)->load('C:\dane\nauczyciele\numery_uczniow\importujNumery.xlsx', function($reader) {
            $students = $reader->get();
            foreach($students as $student) {
                echo '<hr />';
                $student_id = $this -> findStudent($student);
                if($student_id)
                    // sprawdzenie czy numer ucznia w bazie danych się zgadza
                    $this -> checkStudentNumber($student_id, $student);
                else    printf('<p>Błąd importu numeru %d dla ucznia %s %s %s.</p>', $student['nr'], $student['nazwisko'], $student['imie'], $student['drugie_imie']);
            }
        });
        echo '</section>';
        return view('studentNumber.import');
    }

    private function findStudent($student) {
        // znalezienie ucznia w bazie danych
        // sprawdzenie czy jest osoba o podanym nazwisku i imionach
        $student_id = $this->studentRepo -> findStudentIdByGradeAndName($this->grade, $student['nazwisko'], $student['imie'], $student['drugie_imie']);
        if($student_id < 0) {
            printf('<p style="color: blue; background: #aaf;">Znaleziono kilku uczniów z podanym nazwiskiem %s i imionami %s %s.</p>', $student['nazwisko'], $student['imie'], $student['drugie_imie']);
            return 0;
        }
        if($student_id == 0) {
            printf('<p style="color: #f60;">Nie znaleziono ucznia %s %s %s.</p>', $student['nazwisko'], $student['imie'], $student['drugie_imie']);
            return 0;
        }    
        return $student_id;
    }

    private function checkStudentNumber($student_id, $student) {
        $numbers = StudentNumber::where('student_id', '=', $student_id) -> where('grade_id', '=', $this->grade)
            -> where('school_year_id', '=', $this->schoolYear) -> get();
        foreach($numbers as $number) {
            if($number['number'] == $student['nr'])
                printf('<p>Dla ucznia <strong>%s %s %s</strong> numer w bazie zgadza się z importowanym [%s = %s].</p>',
                    $student['nazwisko'], $student['imie'], $student['drugie_imie'], $number['number'], $student['nr']);
            else {
                printf('<p style="background: orange;">Dla ucznia <strong>%s %s %s</strong> numer w bazie %s nie zgadza się z importowanym %s.</p>',
                    $student['nazwisko'], $student['imie'], $student['drugie_imie'], $number['number'], $student['nr']);
                printf('<p style="background: red;">Dopisz odpowiednią funkcję dla tego przypadku</p>');
            }
        }
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

/*
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
*/
}