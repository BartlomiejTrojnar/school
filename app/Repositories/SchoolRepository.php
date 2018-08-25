<?php
namespace App\Repositories;
use App\Models\School;
use Illuminate\Support\Facades\DB;

class SchoolRepository extends BaseRepository {
    public function __construct(School $model) {
        $this->model = $model;
    }
}
?>