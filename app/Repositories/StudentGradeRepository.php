<?php
// ------------------------ (C) mgr inż. Bartłomiej Trojnar; 13.09.2022 ------------------------ //
namespace App\Repositories;
use App\Models\StudentGrade;

class StudentGradeRepository extends BaseRepository {
   public function __construct(StudentGrade $model)  { $this->model = $model; }

   public function getStudentGrades($student_id) {
      return $this->model -> select('student_grades.*')
         -> join('students', 'students.id', '=', 'student_grades.student_id')
         -> where('student_id', '=', $student_id)
         -> orderBy( session()->get('StudentGradeOrderBy[0]'), session()->get('StudentGradeOrderBy[1]') )
         -> orderBy( session()->get('StudentGradeOrderBy[2]'), session()->get('StudentGradeOrderBy[3]') )
         -> orderBy( session()->get('StudentGradeOrderBy[4]'), session()->get('StudentGradeOrderBy[5]') )
         -> get();
   }
/*
   public function getGradeStudentsCompletedPreviousYear($grade_id, $end='2017-08-31') {
      $end = date( 'Y-m-d', strtotime($end.' -1 year') );
      return $this->model -> join('students', 'students.id', '=', 'student_grades.student_id')
         -> where('grade_id', '=', $grade_id) -> where('end', '=', $end)
         -> orderBy( 'last_name' ) -> orderBy( 'first_name' )
         -> get();
   }
*/
   public function getStudentsFromGradeOrderByLastName($grade_id) {
      $records = $this->model -> select('student_grades.*') -> join('students', 'students.id', '=', 'student_grades.student_id') -> where('grade_id', '=', $grade_id)
         -> orderBy( 'last_name', 'asc' ) -> orderBy( 'first_name', 'asc' ) -> orderBy( 'second_name', 'asc' ) -> get();
      return $records;
   }

   public function getStudentsFromGrades($grades, $start=0, $end=0) {
      $records = $this->model -> select('student_grades.*')
         -> join('students', 'students.id', '=', 'student_grades.student_id');
      foreach($grades as $grade)
         $records = $records -> orWhere('student_grades.grade_id', '=', $grade);
      if($start)
         $records = $records -> where('start', '<=', $start);
      if($end)
         $records = $records -> where('end', '>=', $end);
      return $records
         -> orderBy( 'last_name', 'asc')
         -> orderBy( session()->get('StudentGradeOrderBy[2]'), session()->get('StudentGradeOrderBy[3]') )
         -> orderBy( session()->get('StudentGradeOrderBy[4]'), session()->get('StudentGradeOrderBy[5]') )
         -> get();
   }

   public function getStudentsFromGradesOrderByLastName($grades, $start=0, $end=0) {
      $records = $this->model -> select('student_grades.*')
         -> join('students', 'students.id', '=', 'student_grades.student_id');
      foreach($grades as $grade)
         $records = $records -> orWhere('student_grades.grade_id', '=', $grade);
      if($start)
         $records = $records -> where('start', '<=', $start);
      if($end)
         $records = $records -> where('end', '>=', $end);
      return $records
         -> orderBy( 'grade_id', 'asc' )
         -> orderBy( 'students.last_name', 'asc' )
         -> orderBy( 'students.first_name', 'asc' )
         -> get();
   }

   public function getAllSorted() {
      $records = $this->model -> select('student_grades.*')
         -> join('students', 'students.id', '=', 'student_grades.student_id');
      return $records
         -> orderBy( session()->get('StudentGradeOrderBy[0]'), session()->get('StudentGradeOrderBy[1]') )
         -> orderBy( session()->get('StudentGradeOrderBy[2]'), session()->get('StudentGradeOrderBy[3]') )
         -> orderBy( session()->get('StudentGradeOrderBy[4]'), session()->get('StudentGradeOrderBy[5]') )
         -> get();
   }

   public function getActualClassForStudent($student, $dateView) {
      $studentGrades = $this->model -> where('student_id', '=', $student)
         -> where('start', '<=', $dateView)
         -> where('end', '>=', $dateView) -> get();
      if(!count($studentGrades)) return 0;
      return $studentGrades[0]->grade_id;
   }
}
?>