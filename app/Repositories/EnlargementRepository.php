<?php
// ------------------------ (C) mgr inż. Bartłomiej Trojnar; 31.10.2022 ------------------------ //
namespace App\Repositories;
use App\Models\Enlargement;

class EnlargementRepository extends BaseRepository {
   public function __construct(Enlargement $model)  { $this->model = $model; }

   public function getFilteredAndSorted($grade_id=0, $student_id=0) {
      return $this -> findEnlargements($grade_id, $student_id) -> get();
   }

   private function findEnlargements($grade_id=0, $student_id=0) {
      $records = $this->model -> select('enlargements.*')
         -> leftjoin('student_grades', 'student_grades.student_id', '=', 'enlargements.student_id');
      if($grade_id) {   $records = $records -> where('student_grades.grade_id', '=', $grade_id);   }
      if($student_id) $records = $records -> where('enlargements.student_id', '=', $student_id);
      $records = $records -> groupBy('enlargements.id')
         -> orderBy( session() -> get('EnlargementOrderBy[0]'), session() -> get('EnlargementOrderBy[1]') )
         -> orderBy( session() -> get('EnlargementOrderBy[2]'), session() -> get('EnlargementOrderBy[3]') )
         -> orderBy( session() -> get('EnlargementOrderBy[4]'), session() -> get('EnlargementOrderBy[5]') );
      return $records;
   }
}
?>