<?php
namespace App\Repositories;
use App\Models\Task;
use Illuminate\Support\Facades\DB;

class TaskRepository extends BaseRepository {
    public function __construct(Task $model) {
        $this->model = $model;
    }
}
?>