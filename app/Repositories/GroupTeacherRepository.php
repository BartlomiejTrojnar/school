<?php
namespace App\Repositories;
use App\Models\GroupTeacher;
use Illuminate\Support\Facades\DB;

class GroupTeacherRepository extends BaseRepository {
   public function __construct(GroupTeacher $model) { $this->model = $model; }
   public function getGroupTeacherForGroup($group_id) { return $this->model -> where('group_id', '=', $group_id) -> get(); }
}
?>