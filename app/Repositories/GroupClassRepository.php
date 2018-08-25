<?php
namespace App\Repositories;
use App\Models\GroupClass;

class GroupClassRepository extends BaseRepository {
    public function __construct(GroupClass $model) {
        $this->model = $model;
    }
}
?>