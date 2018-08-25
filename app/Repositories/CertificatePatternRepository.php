<?php
namespace App\Repositories;
use App\Models\CertificatePattern;
use Illuminate\Support\Facades\DB;

class CertificatePatternRepository extends BaseRepository {
    public function __construct(CertificatePattern $model) {
        $this->model = $model;
    }
}
?>