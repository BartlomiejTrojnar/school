<?php
namespace App\Repositories;
use App\Models\TaskRating;
use Illuminate\Support\Facades\DB;

class TaskRatingRepository extends BaseRepository {
    public function __construct(TaskRating $model) {
        $this->model = $model;
    }
}
?>