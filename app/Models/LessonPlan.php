<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class LessonPlan extends Model {
   public function group()  { return $this->belongsTo(Group::class); }
   public function lessonHour()  { return $this->belongsTo(LessonHour::class); }
   public function classroom()  { return $this->belongsTo(Classroom::class); }
}
