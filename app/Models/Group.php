<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Group extends Model {
   public function __construct() {
      if( empty(session() -> get('GroupOrderBy[0]')) )    session() -> put('GroupOrderBy[0]', 'groups.id');
      if( empty(session() -> get('GroupOrderBy[1]')) )    session() -> put('GroupOrderBy[1]', 'asc');
      if( empty(session() -> get('GroupOrderBy[2]')) )    session() -> put('GroupOrderBy[2]', 'groups.id');
      if( empty(session() -> get('GroupOrderBy[3]')) )    session() -> put('GroupOrderBy[3]', 'asc');
      if( empty(session() -> get('GroupOrderBy[4]')) )    session() -> put('GroupOrderBy[4]', 'groups.id');
      if( empty(session() -> get('GroupOrderBy[5]')) )    session() -> put('GroupOrderBy[5]', 'asc');
   }

   public function subject()  { return $this->belongsTo(Subject::class); }
   public function grades()  { return $this->hasMany(GroupGrade::class); }
   public function teachers()  { return $this->hasMany(GroupTeacher::class); }
   public function students()  { return $this->hasMany(GroupStudent::class); }
   public function lessonPlans()  { return $this->hasMany(LessonPlan::class); }
   public function lessons()  { return $this->hasMany(Lesson::class); }

   public function studentsCurrent()  {
      $start = session()->get('dateView');
      $end = session()->get('dateEnd');
      if($end)   return $this->hasMany(GroupStudent::class) -> where('start', '<=', $start) -> where('end', '>=', $end);
      return $this->hasMany(GroupStudent::class) -> where('start', '<=', $start) -> where('end', '>=', $start);
   }
}