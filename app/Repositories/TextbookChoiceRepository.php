<?php
namespace App\Repositories;
use App\Models\TextbookChoice;
use Illuminate\Support\Facades\DB;

class TextbookChoiceRepository extends BaseRepository {
    public function __construct(TextbookChoice $model) {
        $this->model = $model;
    }
}
?>