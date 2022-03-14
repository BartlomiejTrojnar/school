<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Declaration extends Model {
   public function __construct() {
      if( empty(session() -> get('DeclarationOrderBy[0]')) )    session() -> put('DeclarationOrderBy[0]', 'session_id');
      if( empty(session() -> get('DeclarationOrderBy[1]')) )    session() -> put('DeclarationOrderBy[1]', 'desc');
      if( empty(session() -> get('DeclarationOrderBy[2]')) )    session() -> put('DeclarationOrderBy[2]', 'id');
      if( empty(session() -> get('DeclarationOrderBy[3]')) )    session() -> put('DeclarationOrderBy[3]', 'asc');
      if( empty(session() -> get('DeclarationOrderBy[4]')) )    session() -> put('DeclarationOrderBy[4]', 'id');
      if( empty(session() -> get('DeclarationOrderBy[5]')) )    session() -> put('DeclarationOrderBy[5]', 'asc');
   }

   public function student()  { return $this->belongsTo(Student::class); }
   public function session()  { return $this->belongsTo(Session::class); }
   public function exams()  { return $this->hasMany(Exam::class); }
}
