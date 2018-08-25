<?php
namespace App\Repositories;
use App\Models\Lesson;
use Illuminate\Support\Facades\DB;

class LessonRepository extends BaseRepository {
    public function __construct(Lesson $model) {
        $this->model = $model;
    }
}
?>