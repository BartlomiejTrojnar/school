<?php
// ------------------------ (C) mgr inż. Bartłomiej Trojnar; 19.12.2022 ------------------------ //
namespace App\Repositories;
use App\Models\Declaration;

use App\Models\Grade;
use App\Repositories\GradeRepository;

class DeclarationRepository extends BaseRepository {
   public function __construct(Declaration $model)  { 
      $this->model = $model;
   }

   public function getFilteredAndSortedAndPaginate($session_id, $grade_id, $student_id=0) {
      $records = $this->model -> select('declarations.*')
         -> leftjoin('student_grades', 'student_grades.student_id', '=', 'declarations.student_id')
         -> leftjoin('students', 'students.id', '=', 'declarations.student_id');
      if($grade_id) {
         $gradeRepo = new GradeRepository(new Grade);
         $grade = $gradeRepo -> find($grade_id);
         $date = $grade->year_of_graduation.'-04-20';
         $records = $records -> where('grade_id', '=', $grade_id) -> where('date_start', '<=', $date) -> where('date_end', '>=', $date);
      }
      if($session_id) $records = $records -> where('session_id', '=', $session_id);
      if($student_id) $records = $records -> where('declarations.student_id', '=', $student_id);
      $records = $records
         -> orderBy( session() -> get('DeclarationOrderBy[0]'), session() -> get('DeclarationOrderBy[1]') )
         -> orderBy( session() -> get('DeclarationOrderBy[2]'), session() -> get('DeclarationOrderBy[3]') )
         -> orderBy( session() -> get('DeclarationOrderBy[4]'), session() -> get('DeclarationOrderBy[5]') )
         -> distinct() -> paginate(25);
      return $records;
   }

   public function getFilteredAndSorted($session_id, $grade_id=0, $student_id=0) {
      $records = $this->model -> select('declarations.*')
         -> leftjoin('student_grades', 'student_grades.student_id', '=', 'declarations.student_id')
         -> leftjoin('students', 'students.id', '=', 'declarations.student_id');
      if($grade_id) {
         $gradeRepo = new GradeRepository(new Grade);
         $grade = $gradeRepo -> find($grade_id);
         $date = $grade->year_of_graduation.'-04-20';
         $records = $records -> where('grade_id', '=', $grade_id) -> where('start', '<=', $date) -> where('end', '>=', $date);
      }
      if($session_id) $records = $records -> where('session_id', '=', $session_id);
      if($student_id) $records = $records -> where('student_id', '=', $student_id);
      return $records
         -> orderBy( session() -> get('DeclarationOrderBy[0]'), session() -> get('DeclarationOrderBy[1]') )
         -> orderBy( session() -> get('DeclarationOrderBy[2]'), session() -> get('DeclarationOrderBy[3]') )
         -> orderBy( session() -> get('DeclarationOrderBy[4]'), session() -> get('DeclarationOrderBy[5]') )
         -> get();
   }
}
?>