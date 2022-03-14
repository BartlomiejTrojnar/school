<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model {
   public $timestamps = false;

   public function __construct() {
      if( empty(session() -> get('SubjectOrderBy[0]')) )    session() -> put('SubjectOrderBy[0]', 'id');
      if( empty(session() -> get('SubjectOrderBy[1]')) )    session() -> put('SubjectOrderBy[1]', 'asc');
      if( empty(session() -> get('SubjectOrderBy[2]')) )    session() -> put('SubjectOrderBy[2]', 'id');
      if( empty(session() -> get('SubjectOrderBy[3]')) )    session() -> put('SubjectOrderBy[3]', 'asc');
      if( empty(session() -> get('SubjectOrderBy[4]')) )    session() -> put('SubjectOrderBy[4]', 'id');
      if( empty(session() -> get('SubjectOrderBy[5]')) )    session() -> put('SubjectOrderBy[5]', 'asc');
   }

   public function teachers()  { return $this->hasMany(TaughtSubject::class); }
   public function enlaregements()  { return $this->hasMany(Enlaregement::class); }
   public function groups()  { return $this->hasMany(Group::class); }
   public function textbooks()  { return $this->hasMany(Textbook::class); }
   public function examDescriptions()  { return $this->hasMany(ExamDescription::class); }
}