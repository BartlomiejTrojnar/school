<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model {
   public function __construct() {
      if( empty(session() -> get('LessonOrderBy[0]')) )    session() -> put('LessonOrderBy[0]', 'number');
      if( empty(session() -> get('LessonOrderBy[1]')) )    session() -> put('LessonOrderBy[1]', 'asc');
      if( empty(session() -> get('LessonOrderBy[2]')) )    session() -> put('LessonOrderBy[2]', 'date');
      if( empty(session() -> get('LessonOrderBy[3]')) )    session() -> put('LessonOrderBy[3]', 'desc');
   }

   public function group() {   return $this->belongsTo(Group::class); }
   public function teacher() { return $this->belongsTo(Teacher::class); }
}
