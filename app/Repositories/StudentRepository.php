<?php
namespace App\Repositories;
use App\Models\Student;
use Illuminate\Support\Facades\DB;

class StudentRepository extends BaseRepository {
  public function __construct(Student $model) {
      $this->model = $model;
  }

  public function getAllSorted() {
      return $this->model
        -> orderBy( session()->get('StudentOrderBy[0]'), session()->get('StudentOrderBy[1]') )
        -> orderBy( session()->get('StudentOrderBy[2]'), session()->get('StudentOrderBy[3]') )
        -> orderBy( session()->get('StudentOrderBy[4]'), session()->get('StudentOrderBy[5]') )
        -> get();
  }

  public function getAllSortedPaginate($records) {
      return $records
        -> orderBy( session()->get('StudentOrderBy[0]'), session()->get('StudentOrderBy[1]') )
        -> orderBy( session()->get('StudentOrderBy[2]'), session()->get('StudentOrderBy[3]') )
        -> orderBy( session()->get('StudentOrderBy[4]'), session()->get('StudentOrderBy[5]') )
        -> distinct() -> paginate(50);
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