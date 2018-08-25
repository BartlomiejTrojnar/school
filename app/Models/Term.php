<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Term extends Model
{
    public function examDescription()
    {
        return $this->belongsTo(ExamDescription::class);
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    public function exams()
    {
        return $this->hasMany(Exam::class);
    }
}