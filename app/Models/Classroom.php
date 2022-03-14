<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Classroom extends Model {
   public $timestamps = false;

   public function __construct() {
      if( empty(session() -> get('ClassroomOrderBy[0]')) )    session() -> put('ClassroomOrderBy[0]', 'id');
      if( empty(session() -> get('ClassroomOrderBy[1]')) )    session() -> put('ClassroomOrderBy[1]', 'asc');
      if( empty(session() -> get('ClassroomOrderBy[2]')) )    session() -> put('ClassroomOrderBy[2]', 'id');
      if( empty(session() -> get('ClassroomOrderBy[3]')) )    session() -> put('ClassroomOrderBy[3]', 'asc');
      if( empty(session() -> get('ClassroomOrderBy[4]')) )    session() -> put('ClassroomOrderBy[4]', 'id');
      if( empty(session() -> get('ClassroomOrderBy[5]')) )    session() -> put('ClassroomOrderBy[5]', 'asc');
   }

   public function teachers()  { return $this->hasMany(Teacher::class); }
   public function lessonPlans()  { return $this->hasMany(LessonPlan::class); }
   public function terms()  { return $this->hasMany(Term::class); }
}
