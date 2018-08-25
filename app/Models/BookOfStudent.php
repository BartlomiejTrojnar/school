<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class BookOfStudent extends Model
{
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }
}
