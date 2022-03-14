<?php
// -------------------- (C) mgr inż. Bartłomiej Trojnar; (II) grudzień 2020 -------------------- //
namespace App\Repositories;
use App\Models\Task;
use Illuminate\Support\Facades\DB;

class TaskRepository extends BaseRepository {
   public function __construct(Task $model)  { $this->model = $model; }

   public function getAllSorted() {
      return $this->model
         -> orderBy( session()->get('TaskOrderBy[0]'), session()->get('TaskOrderBy[1]') )
         -> orderBy( session()->get('TaskOrderBy[2]'), session()->get('TaskOrderBy[3]') )
         -> orderBy( session()->get('TaskOrderBy[4]'), session()->get('TaskOrderBy[5]') )
         -> get();
   }
}
?>