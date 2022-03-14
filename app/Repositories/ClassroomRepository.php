<?php
namespace App\Repositories;
use App\Models\Classroom;
use Illuminate\Support\Facades\DB;

class ClassroomRepository extends BaseRepository {
   public function __construct(Classroom $model)  { $this->model = $model; }

   public function getAllSorted() {
      return $this->model
         -> orderBy( session()->get('ClassroomOrderBy[0]'), session()->get('ClassroomOrderBy[1]') )
         -> orderBy( session()->get('ClassroomOrderBy[2]'), session()->get('ClassroomOrderBy[3]') )
         -> orderBy( session()->get('ClassroomOrderBy[4]'), session()->get('ClassroomOrderBy[5]') )
         -> get();
   }
}
?>