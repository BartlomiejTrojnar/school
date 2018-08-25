<?php
namespace App\Repositories;
use App\Models\CommandRating;
use Illuminate\Support\Facades\DB;

class CommandRatingRepository extends BaseRepository {
    public function __construct(CommandRating $model) {
        $this->model = $model;
    }
}
?>