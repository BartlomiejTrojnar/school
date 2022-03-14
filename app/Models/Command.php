<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Command extends Model {
   public function __construct() {
      if(empty(session()->get('CommandOrderBy[0]')))    session()->put('CommandOrderBy[0]', 'id');
      if(empty(session()->get('CommandOrderBy[1]')))    session()->put('CommandOrderBy[1]', 'asc');
      if(empty(session()->get('CommandOrderBy[2]')))    session()->put('CommandOrderBy[2]', 'id');
      if(empty(session()->get('CommandOrderBy[3]')))    session()->put('CommandOrderBy[3]', 'desc');
      if(empty(session()->get('CommandOrderBy[4]')))    session()->put('CommandOrderBy[4]', 'id');
      if(empty(session()->get('CommandOrderBy[5]')))    session()->put('CommandOrderBy[5]', 'desc');
   }

   public function task()  { return $this->belongsTo(Task::class); }
   public function commandRatings()  { return $this->hasMany(CommandRating::class); }
}