<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Certificate extends Model {
   public $timestamps = false;

   public function student()   { return $this->belongsTo(Student::class); }
   public function template()  { return $this->belongsTo(CertificateTemplate::class); }
}
