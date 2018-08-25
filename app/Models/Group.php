<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function grades()
    {
        return $this->hasMany(GroupClass::class);
    }

    public function teachers()
    {
        return $this->hasMany(GroupTeacher::class);
    }

    public function students()
    {
        return $this->hasMany(GroupStudent::class);
    }

    public function lessonPlans()
    {
        return $this->hasMany(LessonPlan::class);
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }
}