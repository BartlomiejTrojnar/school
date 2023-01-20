<?php
// ------------------------ (C) mgr inż. Bartłomiej Trojnar; 25.10.2022 ------------------------ //
namespace App\Repositories;
use App\Models\Certificate;
use Illuminate\Support\Facades\DB;

class CertificateRepository extends BaseRepository {
   public function __construct(Certificate $model)  { $this->model = $model; }

   public function getFilteredAndSorted($student_id=0) {
      return $this -> findCertificates($student_id) -> get();
   }

   private function findCertificates($student_id=0) {
      $records = $this->model;
      if($student_id) $records = $records -> where('certificates.student_id', '=', $student_id);
      //$records = $records -> groupBy('declarations.id')
      //   -> orderBy( session() -> get('DeclarationOrderBy[0]'), session() -> get('DeclarationOrderBy[1]') )
      //   -> orderBy( session() -> get('DeclarationOrderBy[2]'), session() -> get('DeclarationOrderBy[3]') )
      //   -> orderBy( session() -> get('DeclarationOrderBy[4]'), session() -> get('DeclarationOrderBy[5]') );
      return $records;
   }
}
?>