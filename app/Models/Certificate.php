<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function certificatePattern()
    {
        return $this->belongsTo(CertificatePattern::class);
    }

    public function bookOfStudent()
    {
        return $this->belongsTo(BookOfStudent::class);
    }

    public function achievements()
    {
        return $this->hasMany(Achievement::class);
    }
}
