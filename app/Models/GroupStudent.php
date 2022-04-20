<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class GroupStudent extends Model {
   public function __construct() {
      if( empty(session() -> get('GroupStudentOrderBy[0]')) )    session() -> put('GroupStudentOrderBy[0]', 'last_name');
      if( empty(session() -> get('GroupStudentOrderBy[1]')) )    session() -> put('GroupStudentOrderBy[1]', 'asc');
      if( empty(session() -> get('GroupStudentOrderBy[2]')) )    session() -> put('GroupStudentOrderBy[2]', 'first_name');
      if( empty(session() -> get('GroupStudentOrderBy[3]')) )    session() -> put('GroupStudentOrderBy[3]', 'asc');
      if( empty(session() -> get('GroupStudentOrderBy[4]')) )    session() -> put('GroupStudentOrderBy[4]', 'second_name');
      if( empty(session() -> get('GroupStudentOrderBy[5]')) )    session() -> put('GroupStudentOrderBy[5]', 'asc');
   }

   public function group()  { return $this->belongsTo(Group::class); }
   public function student()  { return $this->belongsTo(Student::class); }
   public function ratings()  { return $this->hasMany(FinalRating::class); }
}