<?php
// ------------------------ (C) mgr inż. Bartłomiej Trojnar; 24.06.2023 ------------------------ //
namespace App\Repositories;
use App\Models\Student;

class StudentRepository extends BaseRepository {
   public function __construct(Student $model)  { $this->model = $model; }

   private function filteredAndSortedRecords($grade_id=0, $group_id=0) {
      $records = $this->model->select('students.*');
      if($grade_id) $records = $records -> join('student_grades', 'students.id', '=', 'student_grades.student_id')
         -> where('student_grades.grade_id', '=', $grade_id);
      if($group_id) $records = $records -> join('group_students', 'students.id', '=', 'group_students.student_id')
         -> where('group_students.group_id', '=', $group_id);
      $records = $records
         -> orderBy( session() -> get('StudentOrderBy[0]'), session() -> get('StudentOrderBy[1]') )
         -> orderBy( session() -> get('StudentOrderBy[2]'), session() -> get('StudentOrderBy[3]') )
         -> orderBy( session() -> get('StudentOrderBy[4]'), session() -> get('StudentOrderBy[5]') );
      return $records;
   }

   public function getFilteredAndSorted($grade_id=0, $group_id=0) {
      $records = $this -> filteredAndSortedRecords($grade_id, $group_id);
      return $records -> get();
   }

   public function getFilteredAndSortedAndPaginate($grade_id=0, $group_id=0) {
      $records = $this -> filteredAndSortedRecords($grade_id, $group_id);
      return $records -> paginate(30);
   }

   public function getAllSorted() {
      return $this->model -> select('students.*')
         -> leftjoin('book_of_students', 'students.id', '=', 'book_of_students.student_id')
         -> orderBy( session()->get('StudentOrderBy[0]'), session()->get('StudentOrderBy[1]') )
         -> orderBy( session()->get('StudentOrderBy[2]'), session()->get('StudentOrderBy[3]') )
         -> orderBy( session()->get('StudentOrderBy[4]'), session()->get('StudentOrderBy[5]') )
         -> get();
   }

   public function getAllOrderByLastName() {
      return $this->model -> orderBy( 'last_name', 'asc' ) -> orderBy( 'first_name', 'asc' ) -> get();
   }

   public function sortAndPaginateRecords($records) {
      return $records  -> select('students.*')
         -> leftjoin('book_of_students', 'students.id', '=', 'book_of_students.student_id')
         -> groupBy( 'students.id' )
         -> orderBy( session()->get('StudentOrderBy[0]'), session()->get('StudentOrderBy[1]') )
         -> orderBy( session()->get('StudentOrderBy[2]'), session()->get('StudentOrderBy[3]') )
         -> orderBy( session()->get('StudentOrderBy[4]'), session()->get('StudentOrderBy[5]') )
         -> paginate(50);
   }

   public function countStudentsByDates($start, $end) {
      return $this -> getStudentsByDates($start, $end) -> count();
   }

   public function getStudentsByDates($start, $end) {
      return $this->model
         -> join('student_grades', 'students.id', '=', 'student_grades.student_id')
         -> where('start', '<=', $end)
         -> where('end', '>=', $start)
         -> orderBy( session()->get('StudentOrderBy[0]'), session()->get('StudentOrderBy[1]') )
         -> orderBy( session()->get('StudentOrderBy[2]'), session()->get('StudentOrderBy[3]') )
         -> orderBy( session()->get('StudentOrderBy[4]'), session()->get('StudentOrderBy[5]') )
         -> get();
   }

   public function getStudentsFromGroup($group, $dateView="") {
      $records = $this->model -> select('students.*')
         -> join('group_students', 'students.id', '=', 'group_students.student_id')
         -> where('group_students.group_id', '=', $group);
      if($dateView) $records = $records
         -> where('date_start', '<=', $dateView)
         -> where('date_end', '>=', $dateView);
      return $records
         -> orderBy( session()->get('StudentOrderBy[0]'), session()->get('StudentOrderBy[1]') )
         -> orderBy( session()->get('StudentOrderBy[2]'), session()->get('StudentOrderBy[3]') )
         -> orderBy( session()->get('StudentOrderBy[4]'), session()->get('StudentOrderBy[5]') )
         -> get();
   }

   public function getStudentsFromSchool($school) {
      $records = $this->model -> select('students.*')
         -> join('student_grades', 'students.id', '=', 'student_grades.student_id')
         -> join('grades', 'student_grades.grade_id', '=', 'grades.id')
         -> join('book_of_students', 'students.id', '=', 'book_of_students.student_id')
         -> where('grades.school_id', '=', $school)
         -> where('book_of_students.school_id', '=', $school) -> distinct();
      return $records
         -> orderBy( session()->get('StudentOrderBy[0]'), session()->get('StudentOrderBy[1]') )
         -> orderBy( session()->get('StudentOrderBy[2]'), session()->get('StudentOrderBy[3]') )
         -> orderBy( session()->get('StudentOrderBy[4]'), session()->get('StudentOrderBy[5]') )
         -> paginate(50);
   }

   public function findStudentIdByPesel($pesel) {
      $records = $this->model -> where('pesel', '=', $pesel) -> get();
      if(count($records)==0) return 0;
      if(count($records)>1) return -1;
      return $records[0]['id'];
   }

   public function findStudentIdByName($last_name, $first_name, $second_name='') {
      $records = $this->model -> where('last_name', '=', $last_name) -> where('first_name', '=', $first_name);
      if($second_name)  $records =$records -> where('second_name', '=', $second_name);
      $records = $records -> get();
      if(count($records)==0) return 0;
      if(count($records)>1) return -1;
      return $records[0]['id'];
   }

   public function findStudentIdByGradeAndName($grade_id, $last_name, $first_name, $second_name='') {
      $records = $this->model -> join('student_grades', 'students.id', '=', 'student_grades.student_id')
         -> where('student_grades.grade_id', '=', $grade_id)
         -> where('last_name', '=', $last_name) -> where('first_name', '=', $first_name);
      if($second_name)  $records =$records -> where('second_name', '=', $second_name);
      $records = $records -> get();
      if(count($records)==0) return 0;
      if(count($records)>1) return -1;
      return $records[0]['student_id'];
   }

   public function checkStudentWithNames($student_id, $last_name, $first_name, $second_name='') {
      $records = $this->model -> where('id', '=', $student_id) -> get();
      if($records[0]['last_name'] != $last_name) return false;
      if($records[0]['first_name'] != $first_name) return false;
      if($records[0]['second_name'] != $second_name) return false;
      return true;
   }
}
?>