<?php
namespace App\Repositories;
use App\Models\Exam;
use Illuminate\Support\Facades\DB;

class ExamRepository extends BaseRepository {
    public function __construct(Exam $model) {
        $this->model = $model;
    }
}
?>