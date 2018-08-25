<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class TaskRating extends Model
{
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function commandRatings()
    {
        return $this->hasMany(CommandRating::class);
    }
}
