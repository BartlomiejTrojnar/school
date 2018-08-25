<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Command extends Model
{
    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function commandRatings()
    {
        return $this->hasMany(CommandRating::class);
    }
}
