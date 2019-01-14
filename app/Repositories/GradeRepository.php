<?php
namespace App\Repositories;
use App\Models\Grade;
use Illuminate\Support\Facades\DB;

class GradeRepository extends BaseRepository {
  public function __construct(Grade $model) {
      $this->model = $model;
  }

  public function getAllSorted() {
      return $this->model
        -> orderBy( session()->get('GradeOrderBy[0]'), session()->get('GradeOrderBy[1]') )
        -> orderBy( session()->get('GradeOrderBy[2]'), session()->get('GradeOrderBy[3]') )
        -> get();
  }

  public function getPaginateSorted() {
      return $this->model
        -> orderBy( session()->get('GradeOrderBy[0]'), session()->get('GradeOrderBy[1]') )
        -> orderBy( session()->get('GradeOrderBy[2]'), session()->get('GradeOrderBy[3]') )
        -> paginate(20);
  }

  public function sortAndPaginateRecords($records) {
      return $records
        -> orderBy( session()->get('GradeOrderBy[0]'), session()->get('GradeOrderBy[1]') )
        -> orderBy( session()->get('GradeOrderBy[2]'), session()->get('GradeOrderBy[3]') )
        -> paginate(20);
  }

  public function countGradesInYear($year) {
      $grades = $this->model
        -> where('year_of_beginning', '<=', $year)
        -> where('year_of_graduation', '>=', $year)
        -> get();
      $count = $grades -> count();
      return $count;
  }
}
?>