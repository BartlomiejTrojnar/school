<?php
// ------------------------ (C) mgr inż. Bartłomiej Trojnar; 21.08.2021 ------------------------ //
namespace App\Repositories;
use App\Models\BookOfStudent;
use Illuminate\Support\Facades\DB;

class BookOfStudentRepository extends BaseRepository {
   public function __construct(BookOfStudent $model)  { $this->model = $model; }
   public function getLastNumber()  { return $this->model->all()->last()->number; }

   public function getAllSortedAndPaginate() {
      return $this->model
         -> orderBy( session()->get('BookOfStudentOrderBy[0]'), session()->get('BookOfStudentOrderBy[1]') )
         -> orderBy( session()->get('BookOfStudentOrderBy[2]'), session()->get('BookOfStudentOrderBy[3]') )
         -> orderBy( session()->get('BookOfStudentOrderBy[4]'), session()->get('BookOfStudentOrderBy[5]') )
         -> paginate(30);
   }

   public function sortAndPaginateRecords($records) {
      return $records
         -> orderBy( session()->get('BookOfStudentOrderBy[0]'), session()->get('BookOfStudentOrderBy[1]') )
         -> orderBy( session()->get('BookOfStudentOrderBy[2]'), session()->get('BookOfStudentOrderBy[3]') )
         -> orderBy( session()->get('BookOfStudentOrderBy[4]'), session()->get('BookOfStudentOrderBy[5]') )
         -> paginate(30);
   }

   public function findByNumber($number) {
      return $this->model
         -> where( 'number', '=', $number )
         -> get();
   }

   public function checkStudentNumber($student_id, $school_id) {
      $records = $this->model -> select('number') -> where('student_id', '=', $student_id) -> where('school_id', '=', $school_id) -> get();
      if(count($records)==0) return 0;
      if(count($records)>1) return -1;
      return $records[0]['number'];
   }
}
?>