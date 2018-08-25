<?php
namespace App\Repositories;
use App\Models\Achievement;
use Illuminate\Support\Facades\DB;

class AchievementRepository extends BaseRepository {
    public function __construct(Achievement $model) {
        $this->model = $model;
    }
}
?>