<?php
namespace App\Repositories;
use App\Models\School;
use Illuminate\Support\Facades\DB;

class SchoolRepository extends BaseRepository {
  public function __construct(School $model) {
      $this->model = $model;
  }

  public function getAllSorted() {
      return $this->model
        -> orderBy( session()->get('SchoolOrderBy[0]'), session()->get('SchoolOrderBy[1]') )
        -> orderBy( session()->get('SchoolOrderBy[2]'), session()->get('SchoolOrderBy[3]') )
        -> get();
  }
}
?>