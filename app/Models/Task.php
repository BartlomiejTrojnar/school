<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    public function commands()
    {
        return $this->hasMany(Command::class);
    }

    public function taskRatings()
    {
        return $this->hasMany(TaskRating::class);
    }
}