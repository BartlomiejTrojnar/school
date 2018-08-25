<?php
namespace App\Repositories;
use App\Models\Teacher;
use Illuminate\Support\Facades\DB;

class TeacherRepository extends BaseRepository {
    public function __construct(Teacher $model) {
        $this->model = $model;
    }
}
?>