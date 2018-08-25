<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    public $timestamps = false;

    public function teachers()
    {
        return $this->hasMany(Teacher::class);
    }

    public function lessonPlans()
    {
        return $this->hasMany(LessonPlan::class);
    }

    public function terms()
    {
        return $this->hasMany(Term::class);
    }
}
