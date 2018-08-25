<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class CertificatePattern extends Model
{
    public $timestamps = false;

    public function certificates()
    {
        return $this->hasMany(Certificates::class);
    }
}