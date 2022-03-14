<?php
namespace App\Repositories;
use App\Models\Textbook;
use Illuminate\Support\Facades\DB;

class TextbookRepository extends BaseRepository {
   public function __construct(Textbook $model)  { $this->model = $model; }

   public function prepareQuery() {
      $records = $this->model;
      if($this->subject)   $records = $records -> where('subject_id', '=', $this->subject);
      return $records
         -> orderBy( session()->get('TextbookOrderBy[0]'), session()->get('TextbookOrderBy[1]') )
         -> orderBy( session()->get('TextbookOrderBy[2]'), session()->get('TextbookOrderBy[3]') );
   }

   public function getPaginate($subject) {
      $this->subject    = $subject;
      return $this -> prepareQuery() -> paginate(20);
   }

   public function getAll($subject) {
      $this->subject    = $subject;
      return $this -> prepareQuery() -> get();
   }
}
?>