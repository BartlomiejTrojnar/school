<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class SchoolYear extends Model {
   public $timestamps = false;

   public function teachers()  { return $this->hasMany(Teacher::class); }
   public function textbookChoices()  { return $this->hasMany(TextbookChoice::class); }
}