<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class School extends Model {
   public $timestamps = false;

   public function __construct() {
      if( empty(session() -> get('SchoolOrderBy[0]')) )    session() -> put('SchoolOrderBy[0]', 'id');
      if( empty(session() -> get('SchoolOrderBy[1]')) )    session() -> put('SchoolOrderBy[1]', 'asc');
      if( empty(session() -> get('SchoolOrderBy[2]')) )    session() -> put('SchoolOrderBy[2]', 'id');
      if( empty(session() -> get('SchoolOrderBy[3]')) )    session() -> put('SchoolOrderBy[3]', 'desc');
   }

   public function grades()  { return $this->hasMany(Grade::class); }
   public function students()  { return $this->hasMany(BookOfStudent::class); }
   public function textbooks()  { return $this->hasMany(TextbookChoice::class); }
}
