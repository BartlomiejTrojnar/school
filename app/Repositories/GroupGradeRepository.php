<?php
namespace App\Repositories;
use App\Models\GroupGrade;

class GroupGradeRepository extends BaseRepository {
   public function __construct(GroupGrade $model)  { $this->model = $model; }
}
?>