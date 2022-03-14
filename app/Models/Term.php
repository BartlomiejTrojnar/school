<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Term extends Model
{
   public function __construct() {
      if( empty(session() -> get('TermOrderBy[0]')) )    session() -> put('TermOrderBy[0]', 'id');
      if( empty(session() -> get('TermOrderBy[1]')) )    session() -> put('TermOrderBy[1]', 'asc');
      if( empty(session() -> get('TermOrderBy[2]')) )    session() -> put('TermOrderBy[2]', 'id');
      if( empty(session() -> get('TermOrderBy[3]')) )    session() -> put('TermOrderBy[3]', 'desc');
      if( empty(session() -> get('TermOrderBy[4]')) )    session() -> put('TermOrderBy[4]', 'id');
      if( empty(session() -> get('TermOrderBy[5]')) )    session() -> put('TermOrderBy[5]', 'desc');
   }

   public function exam_description() {  return $this->belongsTo(ExamDescription::class);  }
   public function classroom() {  return $this->belongsTo(Classroom::class);  }
   public function exams() {  return $this->hasMany(Exam::class);  }
}