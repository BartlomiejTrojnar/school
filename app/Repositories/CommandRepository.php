<?php
namespace App\Repositories;
use App\Models\Command;
use Illuminate\Support\Facades\DB;

class CommandRepository extends BaseRepository {
   public function __construct(Command $model)  { $this->model = $model; }

   public function getAllSorted() {
      return $this->model
         -> orderBy( session()->get('CommandOrderBy[0]'), session()->get('CommandOrderBy[1]') )
         -> orderBy( session()->get('CommandOrderBy[2]'), session()->get('CommandOrderBy[3]') )
         -> orderBy( session()->get('CommandOrderBy[4]'), session()->get('CommandOrderBy[5]') )
         -> get();
   }

   public function getTaskCommands($task_id) {
      return $this->model
         -> where('task_id', $task_id)
         -> orderBy( session()->get('CommandOrderBy[0]'), session()->get('CommandOrderBy[1]') )
         -> orderBy( session()->get('CommandOrderBy[2]'), session()->get('CommandOrderBy[3]') )
         -> orderBy( session()->get('CommandOrderBy[4]'), session()->get('CommandOrderBy[5]') )
         -> get();
   }
}
?>