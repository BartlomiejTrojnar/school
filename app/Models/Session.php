<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    public $timestamps = false;

    public function declarations()
    {
        return $this->hasMany(Declaration::class);
    }

    public function examDescriptions()
    {
        return $this->hasMany(ExamDescription::class);
    }
}