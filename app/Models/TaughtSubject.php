<?php
namespace App\Models;
use App\Models\Subject;
use Illuminate\Database\Eloquent\Model;

class TaughtSubject extends Model {
   public $timestamps = false;
   public function teacher()  { return $this->belongsTo(Teacher::class); }
   public function subject()  { return $this->belongsTo(Subject::class); }

   public static function nonTaughtSubjects($taughtSubjects) {
      $ts = array();
      foreach($taughtSubjects as $taughtSubject)  $ts[] = $taughtSubject->subject_id;
      $subjects = Subject::where('actual', true) -> whereNotIn('id', $ts) -> get();
      return $subjects;
   }

   public static function unlearningTeachers($subjectTeachers) {
      $st = array();
      foreach($subjectTeachers as $subjectTeacher) $st[] = $subjectTeacher->teacher_id;
      $schoolYear_id = session() -> get('schoolYearSelected');
      if(!$schoolYear_id) $schoolYear_id=0;

      $teachers = Teacher::where('first_year_id', '<=', $schoolYear_id)
         -> where('last_year_id', '>=', $schoolYear_id)
         -> orWhere('last_year_id', NULL)
         -> whereNotIn('id', $st)
         -> orderBy('last_name') -> get();
      if(!$schoolYear_id) $teachers = Teacher::whereNotIn('id', $st) -> orderBy('last_name') -> get();
      return $teachers;
   }
}