<?php
namespace App\Repositories;
use App\Models\School;
use Illuminate\Support\Facades\DB;

class SchoolRepository extends BaseRepository {
   public function __construct(School $model)  { $this->model = $model; }

   public function getAllSorted() {
      return $this->model
         -> orderBy( session()->get('SchoolOrderBy[0]'), session()->get('SchoolOrderBy[1]') )
         -> orderBy( session()->get('SchoolOrderBy[2]'), session()->get('SchoolOrderBy[3]') )
         -> get();
   }

   public function findSchoolIdByName($name) {
      $records = $this->model -> where('name', '=', $name) -> get();
      if(count($records)==0) return 0;
      if(count($records)>1) return -1;
      return $records[0]['id'];
   }

}
?>