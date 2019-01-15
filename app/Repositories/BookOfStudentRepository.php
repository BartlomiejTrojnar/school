<?php
namespace App\Repositories;
use App\Models\BookOfStudent;
use Illuminate\Support\Facades\DB;

class BookOfStudentRepository extends BaseRepository {
  public function __construct(BookOfStudent $model) {
      $this->model = $model;
  }

  public function getLastNumber() {
      return $this->model->all()->last()->number;
  }

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

}
?>