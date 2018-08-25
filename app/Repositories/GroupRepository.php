<?php
namespace App\Repositories;
use App\Models\Group;
use Illuminate\Support\Facades\DB;

class GroupRepository extends BaseRepository {
    public function __construct(Group $model) {
        $this->model = $model;
    }
}
?>