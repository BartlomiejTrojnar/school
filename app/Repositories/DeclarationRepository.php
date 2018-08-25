<?php
namespace App\Repositories;
use App\Models\Declaration;
use Illuminate\Support\Facades\DB;

class DeclarationRepository extends BaseRepository {
    public function __construct(Declaration $model) {
        $this->model = $model;
    }
}
?>