<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    public function __construct() {
        if(empty(session()->get('GradeOrderBy[0]')))    session()->put('GradeOrderBy[0]', 'id');
        if(empty(session()->get('GradeOrderBy[1]')))    session()->put('GradeOrderBy[1]', 'asc');
        if(empty(session()->get('GradeOrderBy[2]')))    session()->put('GradeOrderBy[2]', 'id');
        if(empty(session()->get('GradeOrderBy[3]')))    session()->put('GradeOrderBy[3]', 'asc');
    }

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
