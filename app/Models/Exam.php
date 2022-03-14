<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
   public function __construct() {
      if( empty(session() -> get('ExamOrderBy[0]')) )    session() -> put('ExamOrderBy[0]', 'id');
      if( empty(session() -> get('ExamOrderBy[1]')) )    session() -> put('ExamOrderBy[1]', 'asc');
      if( empty(session() -> get('ExamOrderBy[2]')) )    session() -> put('ExamOrderBy[2]', 'id');
      if( empty(session() -> get('ExamOrderBy[3]')) )    session() -> put('ExamOrderBy[3]', 'desc');
      if( empty(session() -> get('ExamOrderBy[4]')) )    session() -> put('ExamOrderBy[4]', 'id');
      if( empty(session() -> get('ExamOrderBy[5]')) )    session() -> put('ExamOrderBy[5]', 'desc');
   }

   public function declaration() {  return $this->belongsTo(Declaration::class);  }
   public function examDescription() {  return $this->belongsTo(ExamDescription::class);  }
   public function term() {  return $this->belongsTo(Term::class);  }
}