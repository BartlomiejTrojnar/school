<?php
// ------------------------ (C) mgr inż. Bartłomiej Trojnar; 27.07.2021 ------------------------ //
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Student extends Model {
   public function __construct() {
      if( empty(session() -> get('StudentOrderBy[0]')) )    session() -> put('StudentOrderBy[0]', 'last_name');
      if( empty(session() -> get('StudentOrderBy[1]')) )    session() -> put('StudentOrderBy[1]', 'asc');
      if( empty(session() -> get('StudentOrderBy[2]')) )    session() -> put('StudentOrderBy[2]', 'first_name');
      if( empty(session() -> get('StudentOrderBy[3]')) )    session() -> put('StudentOrderBy[3]', 'desc');
      if( empty(session() -> get('StudentOrderBy[4]')) )    session() -> put('StudentOrderBy[4]', 'second_name');
      if( empty(session() -> get('StudentOrderBy[5]')) )    session() -> put('StudentOrderBy[5]', 'desc');
   }

   public function grades()  { return $this->hasMany(StudentGrade::class); }
   public function bookOfStudents()  { return $this->hasMany(BookOfStudent::class); }
   public function numbers()  { return $this->hasMany(StudentNumber::class); }
   public function enlaregements()  { return $this->hasMany(Enlaregement::class); }
   public function groups()  { return $this->hasMany(GroupStudent::class); }
   public function tasks()  { return $this->hasMany(TaskRating::class); }
   public function certificates()  { return $this->hasMany(Certificate::class); }
   public function declarations()  { return $this->hasMany(Declaration::class); }
}
