<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model {
   public function __construct() {
      if( empty(session() -> get('TeacherOrderBy[0]')) )    session() -> put('TeacherOrderBy[0]', 'id');
      if( empty(session() -> get('TeacherOrderBy[1]')) )    session() -> put('TeacherOrderBy[1]', 'desc');
      if( empty(session() -> get('TeacherOrderBy[2]')) )    session() -> put('TeacherOrderBy[2]', 'id');
      if( empty(session() -> get('TeacherOrderBy[3]')) )    session() -> put('TeacherOrderBy[3]', 'asc');
      if( empty(session() -> get('TeacherOrderBy[4]')) )    session() -> put('TeacherOrderBy[4]', 'id');
      if( empty(session() -> get('TeacherOrderBy[5]')) )    session() -> put('TeacherOrderBy[5]', 'asc');
   }

   public function classroom()  { return $this->belongsTo(Classroom::class); }
   public function first_year()  { return $this->belongsTo(SchoolYear::class, 'first_year_id'); }
   public function last_year()  { return $this->belongsTo(SchoolYear::class, 'last_year_id'); }
   public function subjects()  { return $this->hasMany(TaughtSubject::class); }
   public function groups()  { return $this->hasMany(GroupTeacher::class); }
   public function lessons()  { return $this->hasMany(Lesson::class); }
   public function textbookChoices()  { return $this->hasMany(TextbookChoice::class); }
}