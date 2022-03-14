<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class StudentNumber extends Model {
   public $timestamps = false;

   public function __construct() {
      if( empty(session() -> get('StudentNumberOrderBy[0]')) )    session() -> put('StudentNumberOrderBy[0]', 'school_year_id');
      if( empty(session() -> get('StudentNumberOrderBy[1]')) )    session() -> put('StudentNumberOrderBy[1]', 'asc');
      if( empty(session() -> get('StudentNumberOrderBy[2]')) )    session() -> put('StudentNumberOrderBy[2]', 'number');
      if( empty(session() -> get('StudentNumberOrderBy[3]')) )    session() -> put('StudentNumberOrderBy[3]', 'asc');
   }

   public function grade()  { return $this->belongsTo(Grade::class); }
   public function student()  { return $this->belongsTo(Student::class); }
   public function school_year()  { return $this->belongsTo(SchoolYear::class); }
}