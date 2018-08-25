<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ExamDescription extends Model
{
    public function session()
    {
        return $this->belongsTo(Session::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function terms()
    {
        return $this->hasMany(Term::class);
    }

    public function exams()
    {
        return $this->hasMany(Exam::class);
    }
}
