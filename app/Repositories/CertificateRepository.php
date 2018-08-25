<?php
namespace App\Repositories;
use App\Models\Certificate;
use Illuminate\Support\Facades\DB;

class CertificateRepository extends BaseRepository {
    public function __construct(Certificate $model) {
        $this->model = $model;
    }
}
?>