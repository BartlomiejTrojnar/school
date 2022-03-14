<?php
namespace App\Repositories;
use App\Models\LessonHour;

class LessonHourRepository extends BaseRepository {
   public function __construct(LessonHour $model)  { $this->model = $model; }
   public function getAll()  { return $this->model -> get(); }
}
?>