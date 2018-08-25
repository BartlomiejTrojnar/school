<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class StudentClass extends Model
{
    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
