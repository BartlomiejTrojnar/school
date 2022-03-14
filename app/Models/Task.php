<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Task extends Model {
   public function __construct() {
      if( empty(session() -> get('TaskOrderBy[0]')) )    session() -> put('TaskOrderBy[0]', 'id');
      if( empty(session() -> get('TaskOrderBy[1]')) )    session() -> put('TaskOrderBy[1]', 'asc');
      if( empty(session() -> get('TaskOrderBy[2]')) )    session() -> put('TaskOrderBy[2]', 'id');
      if( empty(session() -> get('TaskOrderBy[3]')) )    session() -> put('TaskOrderBy[3]', 'desc');
      if( empty(session() -> get('TaskOrderBy[4]')) )    session() -> put('TaskOrderBy[4]', 'id');
      if( empty(session() -> get('TaskOrderBy[5]')) )    session() -> put('TaskOrderBy[5]', 'desc');
   }

   public function commands()  { return $this->hasMany(Command::class); }
   public function taskRatings()  { return $this->hasMany(TaskRating::class); }
}