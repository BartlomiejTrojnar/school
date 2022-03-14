<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class TextbookChoice extends Model {
   public function __construct() {
      if( empty(session() -> get('TextbookChoiceOrderBy[0]')) )    session() -> put('TextbookChoiceOrderBy[0]', 'id');
      if( empty(session() -> get('TextbookChoiceOrderBy[1]')) )    session() -> put('TextbookChoiceOrderBy[1]', 'asc');
      if( empty(session() -> get('TextbookChoiceOrderBy[2]')) )    session() -> put('TextbookChoiceOrderBy[2]', 'id');
      if( empty(session() -> get('TextbookChoiceOrderBy[3]')) )    session() -> put('TextbookChoiceOrderBy[3]', 'asc');
   }

   public function textbook()  { return $this->belongsTo(Textbook::class); }
   public function school()  { return $this->belongsTo(School::class); }
   public function schoolYear()  { return $this->belongsTo(SchoolYear::class); }
   public function teacher()  { return $this->belongsTo(Teacher::class); }
}
