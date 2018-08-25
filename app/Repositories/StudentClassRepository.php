<?php
namespace App\Repositories;
use App\Models\StudentClass;

class StudentClassRepository extends BaseRepository {
    public function __construct(StudentClass $model) {
        $this->model = $model;
    }

    public function getLastNumber() {
        return 5;
    }
}
?>