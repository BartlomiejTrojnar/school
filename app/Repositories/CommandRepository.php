<?php
namespace App\Repositories;
use App\Models\Command;
use Illuminate\Support\Facades\DB;

class CommandRepository extends BaseRepository {
    public function __construct(Command $model) {
        $this->model = $model;
    }
}
?>