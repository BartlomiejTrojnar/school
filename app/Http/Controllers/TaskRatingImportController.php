<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\BookOfStudent;
use App\Models\Student;
use App\Models\Task;
use App\Models\TaskRating;
use App\Repositories\BookOfStudentRepository;
use App\Repositories\TaskRatingRepository;

class TaskRatingImportController extends Controller
{
    public function import(Request $request) {
        $task = Task::find($request->id);
        $file = 'C:\dane\prace_uczniow\oceny_import.xlsx';
        $this->rows = "";
        $this->importId = 0;
        Excel::selectSheets($task->sheet_name)->load($file, function($reader) use ($request) {
            $results = $reader->get();
            $results->each(function($row) use ($request) {
                $this->rows = $this->rows . $this -> rowCheck($row, $request->id);
            });
        });
        print view('taskRating.importView', ["rows"=>$this->rows]);
    }

    private function rowCheck($row, $taskId) {
        if($row->ksiega == null)    return;
        // odnalezienie ucznia o podanym numerze księgi w bazie danych
        $studentId = $this -> getStudentByNumber($row->ksiega);
        if($studentId)  {
            $student = Student::find($studentId);
            if($student["first_name"] == $row->imie && $student["last_name"] == $row->nazwisko) {
                $taskRating = $this -> findTaskRating($taskId, $studentId, $row->wersja);
                if($taskRating) {
                    $update = false;
                    if($row->termin_zadania != $taskRating->deadline)   $update = true;
                    if($row->data_wykonania != $taskRating->implementation_date)   $update = true;
                    if($row->wersja != $taskRating->version)   $update = true;
                    if($row->waga != $taskRating->importance) $update = true;
                    if($row->data_oceny != $taskRating->rating_date)   $update = true;
                    if($row->punkty != $taskRating->points)   $update = true;
                    if($row->ocena != $taskRating->rating)   $update = true;
                    if($row->uwagi != $taskRating->comments)   $update = true;
                    if($row->data_dziennika != $taskRating->entry_date)   $update = true;

                    if($update) return $this -> rowImportUpdate($taskId, $studentId, $row, $taskRating);
                }
                else return $this -> rowImport($taskId, $studentId, $row);
            }
            else { // uczeń o numerze $row->ksiega nie został odnaleziony
                $komunikat = '<tr><td style="background: red;" colspan="11">'.$student.'<br />'.$row;
                $komunikat.= 'Uczeń ' .$student['first_name']." ".$student['last_name']. ' o numerze '.$row->ksiega.' nie został znaleziony!</td></tr>';
                return $komunikat;
            }   
        }
        else    return '<tr><td style="background: red;" colspan="11">Brak numeru %s w księdze uczniów lub kilka takich numerów.</td></tr>';
    }

    private function getStudentByNumber($number) {
        // odnalezienie ucznia o podanym numerze księgi w bazie danych
        $bookRepo = new BookOfStudentRepository(new BookOfStudent);
        $books = $bookRepo -> findByNumber($number);
        if(count($books) == 1)  return $books[0]->student_id;
        else return -1;
    }

    private function findTaskRating($task_id, $student_id, $version) {
        $taskRatingRepo = new TaskRatingRepository(new TaskRating);
        $taskRatings = $taskRatingRepo -> findTaskRatingForTaskStudentAndVersion($task_id, $student_id, $version);
        if(count($taskRatings) == 1) return $taskRatings[0];
        else return 0;
    }

    private function rowImport($taskId, $studentId, $row) {
        $task = Task::find($taskId);
        return view('taskRating.importForm', ["taskId"=>$taskId, "studentId"=>$studentId, "row"=>$row, "taskName"=>$task->name, "importId"=>++$this->importId]);
    }

    private function rowImportUpdate($taskId, $studentId, $row, $taskRating) {
        return view('taskRating.importUpdateForm', ["taskId"=>$taskId, "studentId"=>$studentId, "row"=>$row, "taskRating"=>$taskRating, "importId"=>++$this->importId]);
    }

    public function store(Request $request) {
        $taskRating = new TaskRating;
        $taskRating->student_id = $request->student_id;
        $taskRating->task_id = $request->task_id;

        $taskRating->deadline = $request->deadline;
        if($request->deadline=="" || $request->deadline=="0000-00-00") $taskRating->deadline = null;
        $taskRating->implementation_date = $request->implementation_date;
        if($request->implementation_date=="" || $request->implementation_date=="0000-00-00") $taskRating->implementation_date = null;

        $taskRating->version = $request->version;
        $taskRating->importance = $request->importance;
        $taskRating->rating_date = $request->rating_date;
        if($request->rating_date=="" || $request->rating_date=="0000-00-00") $taskRating->rating_date = null;
        $taskRating->points = $request->points;
        $taskRating->rating = $request->rating;

        $taskRating->comments = $request->comments;
        if($request->comments=="") $taskRating->comments = null;
        $taskRating->diary = $request->diary;
        $taskRating->entry_date = $request->entry_date;
        if($request->entry_date=="" || $request->entry_date=="0000-00-00") $taskRating->entry_date = null;
        $taskRating -> save();
        return 1;
    }

    public function update(Request $request, TaskRating $taskRating) {
        $taskRating = $taskRating -> find($request->id);

        $taskRating->student_id = $request->student_id;
        $taskRating->task_id = $request->task_id;

        $taskRating->deadline = $request->deadline;
        if($request->deadline=="" || $request->deadline=="0000-00-00") $taskRating->deadline = null;
        $taskRating->implementation_date = $request->implementation_date;
        if($request->implementation_date=="" || $request->implementation_date=="0000-00-00") $taskRating->implementation_date = null;

        $taskRating->version = $request->version;
        $taskRating->importance = $request->importance;
        $taskRating->rating_date = $request->rating_date;
        if($request->rating_date=="" || $request->rating_date=="0000-00-00") $taskRating->rating_date = null;
        $taskRating->points = $request->points;
        $taskRating->rating = $request->rating;

        $taskRating->comments = $request->comments;
        if($request->comments=="") $taskRating->comments = null;
        $taskRating->diary = $request->diary;
        $taskRating->entry_date = $request->entry_date;
        if($request->entry_date=="" || $request->entry_date=="0000-00-00") $taskRating->entry_date = null;
        $taskRating -> save();
        return 1;
    }
}