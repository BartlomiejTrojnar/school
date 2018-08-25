<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    public $timestamps = false;

    public function teachers()
    {
        return $this->hasMany(TaughtSubject::class);
    }

    public function enlaregements()
    {
        return $this->hasMany(Enlaregement::class);
    }

    public function groups()
    {
        return $this->hasMany(Group::class);
    }

    public function textbooks()
    {
        return $this->hasMany(Textbook::class);
    }

    public function examDescriptions()
    {
        return $this->hasMany(ExamDescription::class);
    }
}