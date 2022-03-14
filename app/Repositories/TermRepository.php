<?php
namespace App\Repositories;
use App\Models\Term;
use Illuminate\Support\Facades\DB;

class TermRepository extends BaseRepository {
   public function __construct(Term $model) {  $this->model = $model;  }

   public function getFilteredAndSorted($session_id, $exam_description_id, $classroom_id) {
      $records = $this->model;
      if($session_id) $records = $records
         -> join('exam_descriptions', 'exam_descriptions.id', '=', 'terms.exam_description_id')
         -> select('terms.*');

      if($session_id) $records = $records -> where('session_id', '=', $session_id);
      if($exam_description_id) $records = $records -> where('exam_description_id', '=', $exam_description_id);
      if($classroom_id) $records = $records -> where('classroom_id', '=', $classroom_id);
      $records = $records
         -> orderBy( session() -> get('TermOrderBy[0]'), session() -> get('TermOrderBy[1]') )
         -> orderBy( session() -> get('TermOrderBy[2]'), session() -> get('TermOrderBy[3]') )
         -> orderBy( session() -> get('TermOrderBy[4]'), session() -> get('TermOrderBy[5]') )
         -> paginate(25);
      return $records;
   }
}
?>