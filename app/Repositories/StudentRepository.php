<?php
namespace App\Repositories;
use App\Models\Student;
use Illuminate\Support\Facades\DB;

class StudentRepository extends BaseRepository {
    public function __construct(Student $model) {
        $this->model = $model;
    }
}
?>