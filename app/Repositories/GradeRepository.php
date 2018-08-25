<?php
namespace App\Repositories;
use App\Models\Grade;
use Illuminate\Support\Facades\DB;

class GradeRepository extends BaseRepository {
    public function __construct(Grade $model) {
        $this->model = $model;
    }
}
?>