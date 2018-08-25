<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    public function classroom($id=0)
    {
        $classroom = Classroom::find($id);
        return $classroom->name;
          return $this->belongsTo(Classroom::class);
    }

    public function first_year_id()
    {
        return $this->belongsTo(SchoolYear::class, 'first_year_id');
    }

    public function last_year_id()
    {
        return $this->belongsTo(SchoolYear::class, 'last_year_id');
    }

    public function subjects()
    {
        return $this->hasMany(TaughtSubject::class);
    }

    public function groups()
    {
        return $this->hasMany(Group::class);
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }
}