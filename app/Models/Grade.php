<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function students()
    {
        return $this->hasMany(StudentClass::class);
    }

    public function groups()
    {
        return $this->hasMany(GroupClass::class);
    }
}
