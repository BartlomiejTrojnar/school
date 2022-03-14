<?php
namespace App\Repositories;
use App\Models\TaughtSubject;
use Illuminate\Support\Facades\DB;

class TaughtSubjectRepository extends BaseRepository {
   public function __construct(TaughtSubject $model)  { $this->model = $model; }
}
?>