<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class CertificateTemplate extends Model
{
    public $timestamps = false;
    public function exams()  { return $this->hasMany(Certificate::class); }
}