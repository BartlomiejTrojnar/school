<?php
namespace App\Repositories;
use App\Models\GroupGrade;

class GroupGradeRepository extends BaseRepository {
   public function __construct(GroupGrade $model)  { $this->model = $model; }

   public function getGroupGrades($group_id) {
      return $this->model -> select('grade_id') -> where('group_id', '=', $group_id)
      -> orderBy( 'grade_id', 'asc' )
      -> get();
   }
}
?>