<?php
namespace App\Repositories;
use App\Models\Session;
use Illuminate\Support\Facades\DB;

class SessionRepository extends BaseRepository {
    public function __construct(Session $model) {
        $this->model = $model;
    }
}
?>