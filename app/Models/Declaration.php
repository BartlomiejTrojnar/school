<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Declaration extends Model
{
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function session()
    {
        return $this->belongsTo(Session::class);
    }

    public function exams()
    {
        return $this->hasMany(Exam::class);
    }
}
