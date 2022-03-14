<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ExamDescription extends Model {
   public function __construct() {
      if( empty(session() -> get('ExamDescriptionOrderBy[0]')) )    session() -> put('ExamDescriptionOrderBy[0]', 'id');
      if( empty(session() -> get('ExamDescriptionOrderBy[1]')) )    session() -> put('ExamDescriptionOrderBy[1]', 'asc');
      if( empty(session() -> get('ExamDescriptionOrderBy[2]')) )    session() -> put('ExamDescriptionOrderBy[2]', 'id');
      if( empty(session() -> get('ExamDescriptionOrderBy[3]')) )    session() -> put('ExamDescriptionOrderBy[3]', 'asc');
      if( empty(session() -> get('ExamDescriptionOrderBy[4]')) )    session() -> put('ExamDescriptionOrderBy[4]', 'id');
      if( empty(session() -> get('ExamDescriptionOrderBy[5]')) )    session() -> put('ExamDescriptionOrderBy[5]', 'asc');
   }

   public function session()  { return $this->belongsTo(Session::class); }
   public function subject()  { return $this->belongsTo(Subject::class); }
   public function terms()  { return $this->hasMany(Term::class); }
   public function exams()  { return $this->hasMany(Exam::class); }
}
