<?php
namespace App\Repositories;
use App\Models\Subject;
use Illuminate\Support\Facades\DB;

class SubjectRepository extends BaseRepository {
    public function __construct(Subject $model) {
        $this->model = $model;
    }
}
?>