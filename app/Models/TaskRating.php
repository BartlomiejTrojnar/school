<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class TaskRating extends Model {
   public function __construct() {
      if(empty(session()->get('TaskRatingOrderBy[0]')))    session()->put('TaskRatingOrderBy[0]', 'task_ratings.id');
      if(empty(session()->get('TaskRatingOrderBy[1]')))    session()->put('TaskRatingOrderBy[1]', 'asc');
      if(empty(session()->get('TaskRatingOrderBy[2]')))    session()->put('TaskRatingOrderBy[2]', 'task_ratings.id');
      if(empty(session()->get('TaskRatingOrderBy[3]')))    session()->put('TaskRatingOrderBy[3]', 'asc');
      if(empty(session()->get('TaskRatingOrderBy[4]')))    session()->put('TaskRatingOrderBy[4]', 'task_ratings.id');
      if(empty(session()->get('TaskRatingOrderBy[5]')))    session()->put('TaskRatingOrderBy[5]', 'asc');
   }

   public function student()  { return $this->belongsTo(Student::class); }
   public function task()  { return $this->belongsTo(Task::class); }
   public function commandRatings()  { return $this->hasMany(CommandRating::class); }
}
