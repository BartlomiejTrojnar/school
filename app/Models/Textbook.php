<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Textbook extends Model {
   public function __construct() {
      if( empty(session() -> get('TextbookOrderBy[0]')) )    session() -> put('TextbookOrderBy[0]', 'id');
      if( empty(session() -> get('TextbookOrderBy[1]')) )    session() -> put('TextbookOrderBy[1]', 'asc');
      if( empty(session() -> get('TextbookOrderBy[2]')) )    session() -> put('TextbookOrderBy[2]', 'id');
      if( empty(session() -> get('TextbookOrderBy[3]')) )    session() -> put('TextbookOrderBy[3]', 'asc');
   }

   public function subject()  { return $this->belongsTo(Subject::class); }
   public function textbookChoices()  { return $this->hasMany(TextbookChoice::class); }
}
