<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class LessonHour extends Model {
   public $timestamps = false;

   public function lessonPlans()  { return $this->hasMany(LessonPlan::class); }
}