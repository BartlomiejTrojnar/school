<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class BookOfStudent extends Model {
   public function __construct() {
      if( empty(session() -> get('BookOfStudentOrderBy[0]')) )    session() -> put('BookOfStudentOrderBy[0]', 'id');
      if( empty(session() -> get('BookOfStudentOrderBy[1]')) )    session() -> put('BookOfStudentOrderBy[1]', 'asc');
      if( empty(session() -> get('BookOfStudentOrderBy[2]')) )    session() -> put('BookOfStudentOrderBy[2]', 'id');
      if( empty(session() -> get('BookOfStudentOrderBy[3]')) )    session() -> put('BookOfStudentOrderBy[3]', 'asc');
      if( empty(session() -> get('BookOfStudentOrderBy[4]')) )    session() -> put('BookOfStudentOrderBy[4]', 'id');
      if( empty(session() -> get('BookOfStudentOrderBy[5]')) )    session() -> put('BookOfStudentOrderBy[5]', 'asc');
   }

   public function school()  { return $this->belongsTo(School::class); }
   public function student()  { return $this->belongsTo(Student::class); }
   public function certificates()  { return $this->hasMany(Certificate::class); }
}
