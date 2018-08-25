<?php
namespace App\Repositories;
use App\Models\LessonPlan;
use Illuminate\Support\Facades\DB;

class LessonPlanRepository extends BaseRepository {
    public function __construct(LessonPlan $model) {
        $this->model = $model;
    }
}
?>