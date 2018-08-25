<?php
namespace App\Repositories;
use App\Models\Enlargement;
use Illuminate\Support\Facades\DB;

class EnlargementRepository extends BaseRepository {
    public function __construct(Enlargement $model) {
        $this->model = $model;
    }
}
?>