<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\ExamRepository;

class Session extends Model {
   public $timestamps = false;

   public function __construct() {
      if( empty(session() -> get('SessionOrderBy[0]')) )    session() -> put('SessionOrderBy[0]', 'year');
      if( empty(session() -> get('SessionOrderBy[1]')) )    session() -> put('SessionOrderBy[1]', 'desc');
      if( empty(session() -> get('SessionOrderBy[2]')) )    session() -> put('SessionOrderBy[2]', 'id');
      if( empty(session() -> get('SessionOrderBy[3]')) )    session() -> put('SessionOrderBy[3]', 'desc');
   }

   public function declarations()  { return $this->hasMany(Declaration::class); }
   public function examDescriptions()  { return $this->hasMany(ExamDescription::class); }

   public function exams()  {
      $examRepo = new ExamRepository(new Exam);
      return $examRepo -> countSessionExams($this->id);
   }
}