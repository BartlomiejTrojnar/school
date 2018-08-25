<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    public function grades()
    {
        return $this->belongsToMany(Grade::class)->withTimestamps();
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
}
