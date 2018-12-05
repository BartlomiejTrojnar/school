<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class StudentClass extends Model
{
    public function __construct() {
        if(empty(session()->get('StudentClassOrderBy[0]')))    session()->put('StudentClassOrderBy[0]', 'id');
        if(empty(session()->get('StudentClassOrderBy[1]')))    session()->put('StudentClassOrderBy[1]', 'asc');
        if(empty(session()->get('StudentClassOrderBy[2]')))    session()->put('StudentClassOrderBy[2]', 'id');
        if(empty(session()->get('StudentClassOrderBy[3]')))    session()->put('StudentClassOrderBy[3]', 'asc');
        if(empty(session()->get('StudentClassOrderBy[4]')))    session()->put('StudentClassOrderBy[4]', 'id');
        if(empty(session()->get('StudentClassOrderBy[5]')))    session()->put('StudentClassOrderBy[5]', 'asc');
    }

    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
