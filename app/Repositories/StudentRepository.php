<?php
namespace App\Repositories;
use App\Models\Student;
use Illuminate\Support\Facades\DB;

class StudentRepository extends BaseRepository {
  public function __construct(Student $model) {
      $this->model = $model;
  }

  public function countStudentsByDates($date_start, $date_end) {
      $students = $this->model
        -> join('student_classes', 'students.id', '=', 'student_classes.student_id')
        -> where('date_start', '<=', $date_end)
        -> where('date_end', '>=', $date_start)
        -> get();
      $count = $students -> count();
      return $count;
  }
}
?>