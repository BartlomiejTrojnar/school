<?php
// -------------------- (C) mgr inż. Bartłomiej Trojnar; (II) kwiecień 2021 -------------------- //
namespace App\Repositories;
use App\Models\ExamDescription;
use Illuminate\Support\Facades\DB;

class ExamDescriptionRepository extends BaseRepository {
   public function __construct(ExamDescription $model)  { $this->model = $model; }

   private function filteredAndSortedRecords($subject_id, $session_id, $exam_type, $level) {
      $records = $this->model;
      if($subject_id) $records = $records -> where('subject_id', '=', $subject_id);
      if($session_id) $records = $records -> where('session_id', '=', $session_id);
      if($exam_type)  $records = $records -> where('type', '=', $exam_type);
      if($level)      $records = $records -> where('level', '=', $level);
      $records = $records
         -> orderBy( session() -> get('ExamDescriptionOrderBy[0]'), session() -> get('ExamDescriptionOrderBy[1]') )
         -> orderBy( session() -> get('ExamDescriptionOrderBy[2]'), session() -> get('ExamDescriptionOrderBy[3]') )
         -> orderBy( session() -> get('ExamDescriptionOrderBy[4]'), session() -> get('ExamDescriptionOrderBy[5]') );
      return $records;
   }

   public function getFilteredAndSorted($subject_id, $session_id, $exam_type, $level) {
      $records = $this -> filteredAndSortedRecords($subject_id, $session_id, $exam_type, $level);
      return $records -> get();
   }

   public function getFilteredAndSortedAndPaginate($subject_id, $session_id, $exam_type, $level) {
      $records = $this -> filteredAndSortedRecords($subject_id, $session_id, $exam_type, $level);
      return $records -> paginate(20);
   }
}
?>