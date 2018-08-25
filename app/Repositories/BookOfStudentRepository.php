<?php
namespace App\Repositories;
use App\Models\BookOfStudent;
use Illuminate\Support\Facades\DB;

class BookOfStudentRepository extends BaseRepository {
    public function __construct(BookOfStudent $model) {
        $this->model = $model;
    }
}
?>