<?php
// ------------------------ (C) mgr inż. Bartłomiej Trojnar; 30.12.2021 ------------------------ //
namespace App\Repositories;
use App\Models\StudentNumber;

class StudentNumberRepository extends BaseRepository {
   public function __construct(StudentNumber $model)  { $this->model = $model; }
   public function getLastNumber()  { return $this->model->all()->last()->number; }

   public function getGradeNumbers($grade_id) {
      return $this->model -> select('student_numbers.*', 'students.last_name', 'students.first_name')
         -> join('students', 'student_numbers.student_id', '=', 'students.id')
         -> where('grade_id', '=', $grade_id)
         -> orderBy( session()->get('StudentNumberOrderBy[0]'), session()->get('StudentNumberOrderBy[1]') )
         -> orderBy( session()->get('StudentNumberOrderBy[2]'), session()->get('StudentNumberOrderBy[3]') )
         -> get();
   }

   public function getGradeNumbersForSchoolYear($grade_id=0, $school_year_id=0) {
      return $this->model -> select('student_numbers.*', 'students.last_name', 'students.first_name')
         -> join('students', 'student_numbers.student_id', '=', 'students.id')
         -> where('grade_id', '=', $grade_id)
         -> where('school_year_id', '=', $school_year_id)
         -> orderBy( session()->get('StudentNumberOrderBy[0]'), session()->get('StudentNumberOrderBy[1]') )
         -> orderBy( session()->get('StudentNumberOrderBy[2]'), session()->get('StudentNumberOrderBy[3]') )
         -> get();
   }

   public function getStudentNumbers($student_id) {
      return $this->model -> where('student_id', '=', $student_id)
         -> orderBy( session()->get('StudentNumberOrderBy[0]'), session()->get('StudentNumberOrderBy[1]') )
         -> orderBy( session()->get('StudentNumberOrderBy[2]'), session()->get('StudentNumberOrderBy[3]') )
         -> get();
   }
}
?>