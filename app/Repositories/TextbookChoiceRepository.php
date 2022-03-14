<?php
// ------------------------ (C) mgr inż. Bartłomiej Trojnar; 16.10.2021 ------------------------ //
namespace App\Repositories;
use App\Models\TextbookChoice;
use Illuminate\Support\Facades\DB;

class TextbookChoiceRepository extends BaseRepository {
   public function __construct(TextbookChoice $model)  { $this->model = $model; }

   private function prepareQuery() {
      $records = $this->model -> select('textbook_choices.*')
         -> join('textbooks', 'textbooks.id', '=', 'textbook_choices.textbook_id');
      //if($this->textbook)     $records = $records -> where("textbook_id", $this->textbook);
      if($this->school)       $records = $records -> where("school_id", $this->school);
      if($this->schoolYear)   $records = $records -> where("school_year_id", $this->schoolYear);
      if($this->subject)      $records = $records -> where("subject_id", $this->subject);
      if($this->level)        $records = $records -> where("level", $this->level);
      if($this->studyYear)    $records = $records -> where("learning_year", $this->studyYear);

      return $records
         -> orderBy( session()->get('TextbookChoiceOrderBy[0]'), session()->get('TextbookChoiceOrderBy[1]') )
         -> orderBy( session()->get('TextbookChoiceOrderBy[2]'), session()->get('TextbookChoiceOrderBy[3]') );
   }

   public function numberOfChoices($textbook, $school, $schoolYear, $studyYear, $level) {
      $records = $this->model;
      if($textbook)     $records = $records -> where("textbook_id", $textbook);
      if($school)       $records = $records -> where("school_id", $school);
      if($schoolYear)   $records = $records -> where("school_year_id", $schoolYear);
      if($level)        $records = $records -> where("level", $level);
      if($studyYear)    $records = $records -> where("learning_year", $studyYear);
      $records = $records->get();
      return count($records);
   }

   public function getPaginate($school, $schoolYear, $subject, $studyYear, $level) {
      $this->school     = $school;
      $this->schoolYear = $schoolYear;
      $this->subject    = $subject;
      $this->studyYear  = $studyYear;
      $this->level      = $level;
      return $this -> prepareQuery() -> paginate(20);
   }

   public function getForTextbook($id, $school, $schoolYear, $studyYear, $level) {
      $this->school     = $school;
      $this->schoolYear = $schoolYear;
      $this->studyYear  = $studyYear;
      $this->level      = $level;
      $this->subject    = 0;
      $records = $this -> prepareQuery();
      return $records -> where("textbook_id", $id) -> get();
   }
}
?>