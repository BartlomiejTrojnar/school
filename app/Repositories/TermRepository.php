<?php
namespace App\Repositories;
use App\Models\Term;
use Illuminate\Support\Facades\DB;

class TermRepository extends BaseRepository {
    public function __construct(Term $model) {
        $this->model = $model;
    }
}
?>