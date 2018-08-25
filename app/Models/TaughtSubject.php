<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class TaughtSubject extends Model
{
    public $timestamps = false;

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}
