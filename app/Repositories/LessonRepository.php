<?php
namespace App\Repositories;
use App\Models\Lesson;
use Illuminate\Support\Facades\DB;

class LessonRepository extends BaseRepository {
   public function __construct(Lesson $model) { $this->model = $model; }

   public function getGroupLessons($group_id) {
      return $this->model -> where('group_id', '=', $group_id)
         -> orderBy( session()->get('LessonOrderBy[0]'), session()->get('LessonOrderBy[1]') )
         -> get();
   }
}
?>