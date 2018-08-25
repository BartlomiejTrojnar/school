<?php
namespace App\Repositories;
use App\Models\ExamDescription;
use Illuminate\Support\Facades\DB;

class ExamDescriptionRepository extends BaseRepository {
    public function __construct(ExamDescription $model) {
        $this->model = $model;
    }
}
?>