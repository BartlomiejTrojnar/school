<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    public function __construct() {
        if(empty(session()->get('StudentOrderBy[0]')))    session()->put('StudentOrderBy[0]', 'id');
        if(empty(session()->get('StudentOrderBy[1]')))    session()->put('StudentOrderBy[1]', 'asc');
        if(empty(session()->get('StudentOrderBy[2]')))    session()->put('StudentOrderBy[2]', 'id');
        if(empty(session()->get('StudentOrderBy[3]')))    session()->put('StudentOrderBy[3]', 'desc');
        if(empty(session()->get('StudentOrderBy[4]')))    session()->put('StudentOrderBy[4]', 'id');
        if(empty(session()->get('StudentOrderBy[5]')))    session()->put('StudentOrderBy[5]', 'desc');
    }

    public function grades()
    {
        return $this->hasMany(StudentClass::class);
    }

    public function bookOfStudents()
    {
        return $this->hasMany(BookOfStudent::class);
    }

    public function enlaregements()
    {
        return $this->hasMany(Enlaregement::class);
    }

    public function groups()
    {
        return $this->hasMany(GroupStudent::class);
    }

    public function tasks()
    {
        return $this->hasMany(TaskRating::class);
    }

    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }

    public function declarations()
    {
        return $this->hasMany(Declaration::class);
    }
/*
    public function printSelectField($id=0)
    {
       $students = Student::all();
       print '<select name="student_id">';
       print '<option value="0">- wybierz ucznia -</option>';
       foreach($students as $student)
         if($id == $student->id)
           printf('<option selected="selected" value="%d">%s %s %s</option>', $student->id, $student->first_name, $student->second_name, $student->last_name);
         else
           printf('<option value="%d">%s %s %s</option>', $student->id, $student->first_name, $student->second_name, $student->last_name);
       print '</select>';
    }

    public function printSelectFieldSex($sex='')
    {
       print '<select name="sex">';
       print '<option value="0">- płeć -</option>';
       if($sex == 'kobieta')
           print '<option selected="selected" value="1">kobieta</option>';
         else
           print '<option value="1">kobieta</option>';
       if($sex == 'mężczyzna')
           print '<option selected="selected" value="2">mężczyzna</option>';
         else
           print '<option value="2">mężczyzna</option>';
       print '</select>';
    }
*/
}
