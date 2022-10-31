<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Enlargement extends Model
{
    public function __construct() {
        if( empty(session() -> get('EnlargementOrderBy[0]')) )    session() -> put('EnlargementOrderBy[0]', 'student_id');
        if( empty(session() -> get('EnlargementOrderBy[1]')) )    session() -> put('EnlargementOrderBy[1]', 'asc');
        if( empty(session() -> get('EnlargementOrderBy[2]')) )    session() -> put('EnlargementOrderBy[2]', 'subject_id');
        if( empty(session() -> get('EnlargementOrderBy[3]')) )    session() -> put('EnlargementOrderBy[3]', 'asc');
        if( empty(session() -> get('EnlargementOrderBy[4]')) )    session() -> put('EnlargementOrderBy[4]', 'id');
        if( empty(session() -> get('EnlargementOrderBy[5]')) )    session() -> put('EnlargementOrderBy[5]', 'asc');
     }

     public function student()  { return $this->belongsTo(Student::class); }
     public function subject()  { return $this->belongsTo(Subject::class); }
}
