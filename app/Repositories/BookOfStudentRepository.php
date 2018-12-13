<?php
namespace App\Repositories;
use App\Models\BookOfStudent;
use Illuminate\Support\Facades\DB;

class BookOfStudentRepository extends BaseRepository {
    public function __construct(BookOfStudent $model) {
        $this->model = $model;
    }

  public function getAllSorted() {
      return $this->model
        -> orderBy( session()->get('BookOfStudentOrderBy[0]'), session()->get('BookOfStudentOrderBy[1]') )
        -> orderBy( session()->get('BookOfStudentOrderBy[2]'), session()->get('BookOfStudentOrderBy[3]') )
        -> orderBy( session()->get('BookOfStudentOrderBy[4]'), session()->get('BookOfStudentOrderBy[5]') )
        -> get();
  }
}
?>