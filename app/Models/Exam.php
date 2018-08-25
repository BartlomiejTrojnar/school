<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    public function declaration()
    {
        return $this->belongsTo(Declaration::class);
    }

    public function examDescription()
    {
        return $this->belongsTo(ExamDescription::class);
    }

    public function term()
    {
        return $this->belongsTo(Term::class);
    }
}