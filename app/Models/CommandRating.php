<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class CommandRating extends Model
{
    public function command()
    {
        return $this->belongsTo(Command::class);
    }

    public function taskRating()
    {
        return $this->belongsTo(TaskRating::class);
    }
}
