<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    public $timestamps = false;

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    public function students()
    {
        return $this->hasMany(BookOfStudent::class);
    }

    public function textbooks()
    {
        return $this->hasMany(TextbookChoice::class);
    }
}
