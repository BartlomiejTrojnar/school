<?php
namespace App\Repositories;
use App\Models\Classroom;
use Illuminate\Support\Facades\DB;

class ClassroomRepository extends BaseRepository {
    public function __construct(Classroom $model) {
        $this->model = $model;
    }
}
?>