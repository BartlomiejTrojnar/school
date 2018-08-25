<?php
namespace App\Repositories;
use App\Models\Textbook;
use Illuminate\Support\Facades\DB;

class TextbookRepository extends BaseRepository {
    public function __construct(Textbook $model) {
        $this->model = $model;
    }
}
?>