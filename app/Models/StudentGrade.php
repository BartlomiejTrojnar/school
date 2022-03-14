<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class StudentGrade extends Model {
   public function __construct() {
      if( empty(session() -> get('StudentGradeOrderBy[0]')) )    session() -> put('StudentGradeOrderBy[0]', 'id');
      if( empty(session() -> get('StudentGradeOrderBy[1]')) )    session() -> put('StudentGradeOrderBy[1]', 'asc');
      if( empty(session() -> get('StudentGradeOrderBy[2]')) )    session() -> put('StudentGradeOrderBy[2]', 'id');
      if( empty(session() -> get('StudentGradeOrderBy[3]')) )    session() -> put('StudentGradeOrderBy[3]', 'asc');
      if( empty(session() -> get('StudentGradeOrderBy[4]')) )    session() -> put('StudentGradeOrderBy[4]', 'id');
      if( empty(session() -> get('StudentGradeOrderBy[5]')) )    session() -> put('StudentGradeOrderBy[5]', 'asc');
   }

   public function grade()  { return $this->belongsTo(Grade::class); }
   public function student()  { return $this->belongsTo(Student::class); }
}
