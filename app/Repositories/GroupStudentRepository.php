<?php
namespace App\Repositories;
use App\Models\GroupStudent;
use Illuminate\Support\Facades\DB;

class GroupStudentRepository extends BaseRepository {
    public function __construct(GroupStudent $model) {
        $this->model = $model;
    }
}
?>