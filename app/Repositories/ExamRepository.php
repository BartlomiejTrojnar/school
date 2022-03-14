<?php
// ----------------------- (C) mgr inż. Bartłomiej Trojnar; (I) maj 2021 ----------------------- //
namespace App\Repositories;
use App\Models\Exam;
use Illuminate\Support\Facades\DB;

class ExamRepository extends BaseRepository {
   public function __construct(Exam $model) {  $this->model = $model;  }

   public function getFilteredAndSorted($declaration_id, $exam_description_id, $term_id, $classroom_id, $type) {
      $records = $this->model;
      $records = $records -> select('exams.*'); //-> join('terms', 'exams.term_id', '=', 'terms.id');

      if($declaration_id)        $records = $records -> where('declaration_id', '=', $declaration_id);
      if($exam_description_id)   $records = $records -> where('exam_description_id', '=', $exam_description_id);
      if($term_id)               $records = $records -> where('term_id', '=', $term_id);
      if($classroom_id)          $records = $records -> where('classroom_id', '=', $classroom_id);
      if($type)                  $records = $records -> where('type', '=', $type);

      return $records
         -> orderBy( session() -> get('ExamOrderBy[0]'), session() -> get('ExamOrderBy[1]') )
         -> orderBy( session() -> get('ExamOrderBy[2]'), session() -> get('ExamOrderBy[3]') )
         -> orderBy( session() -> get('ExamOrderBy[4]'), session() -> get('ExamOrderBy[5]') )
         -> get();
   }

   public function countExamDescriptionExams($exam_description_id) {
      $countRecords = $this->model -> where('exam_description_id', '=', $exam_description_id) -> count();
      return $countRecords;
   }

   public function getStudentExams($student_id) {
      $records = $this->model
         -> select('exams.*')
         -> join('declarations', 'declarations.id', '=', 'exams.declaration_id')
         -> where('student_id', '=', $student_id)
         -> orderBy( session() -> get('ExamOrderBy[0]'), session() -> get('ExamOrderBy[1]') )
         -> orderBy( session() -> get('ExamOrderBy[2]'), session() -> get('ExamOrderBy[3]') )
         -> orderBy( session() -> get('ExamOrderBy[4]'), session() -> get('ExamOrderBy[5]') )
         -> paginate(25);
      return $records;
   }

   public function countSessionExams($session_id) {
      $records = $this->model;
      $records = $records -> select('exams.*')
         -> join('declarations', 'exams.declaration_id', '=', 'declarations.id')
         -> where('session_id', '=', $session_id) -> count();
      return $records;
   }
}
?>