<?php
// --------------------- (C) mgr inż. Bartłomiej Trojnar; (II) styczeń 2021 --------------------- //
namespace App\Repositories;
use App\Models\TaskRating;
use Illuminate\Support\Facades\DB;

class TaskRatingRepository extends BaseRepository {
   public function __construct(TaskRating $model)  { $this->model = $model; }

   // pobranie ocen zadania dla wybranego zadania //
   public function getTaskRatings($task_id, $student_id=0, $gradeSelected=0, $groupSelected=0, $diaryYesNo="") {
      $results = $this->model -> select('task_ratings.*') -> where('task_id', '=', $task_id);
      if( $student_id )
         $results = $results -> where('student_id', '=', $student_id);
      if( $gradeSelected )
         $results = $results -> join('student_grades', 'task_ratings.student_id', '=', 'student_grades.student_id') -> where('grade_id', '=', $gradeSelected);
      if( $groupSelected )
         $results = $results -> join('group_students', 'task_ratings.student_id', '=', 'group_students.student_id') -> where('group_id', '=', $groupSelected);
      if( $diaryYesNo != "" )   $results = $results -> where('diary', '=', $diaryYesNo);

      $results = $results
         -> orderBy( session()->get('TaskRatingOrderBy[0]'), session()->get('TaskRatingOrderBy[1]') )
         -> orderBy( session()->get('TaskRatingOrderBy[2]'), session()->get('TaskRatingOrderBy[3]') )
         -> orderBy( session()->get('TaskRatingOrderBy[4]'), session()->get('TaskRatingOrderBy[5]') )
         -> get();
      return $results;
   }

   // sprawdzenie ilości ocen z zadania dla wybranego ucznia //
   public function countTaskRatingForStudent($task_id, $student_id) {
      $ratings = $this->model
         -> where('task_id', '=', $task_id)
         -> where('student_id', '=', $student_id)
         -> get();
      return count($ratings);
   }

   // pobranie ocen zadań dla wybranego ucznia //
   public function getStudentTaskRatings($student_id) {
      return $this->model -> where('student_id', '=', $student_id)
         -> orderBy( session()->get('TaskRatingOrderBy[0]'), session()->get('TaskRatingOrderBy[1]') )
         -> orderBy( session()->get('TaskRatingOrderBy[2]'), session()->get('TaskRatingOrderBy[3]') )
         -> orderBy( session()->get('TaskRatingOrderBy[4]'), session()->get('TaskRatingOrderBy[5]') )
         -> get();
   }

   public function getGradeTaskRatings($grade_id) {
      //print_r($grade_id);
      return $this->model -> select('task_ratings.*')
         -> join('students', 'task_ratings.student_id', '=', 'students.id')
         -> join('student_grades', 'students.id', '=', 'student_grades.student_id')
         -> where('grade_id', '=', $grade_id)
         -> orderBy( session()->get('TaskRatingOrderBy[0]'), session()->get('TaskRatingOrderBy[1]') )
         -> orderBy( session()->get('TaskRatingOrderBy[2]'), session()->get('TaskRatingOrderBy[3]') )
         -> orderBy( session()->get('TaskRatingOrderBy[4]'), session()->get('TaskRatingOrderBy[5]') )
         -> get();
   }

   public function findTaskRatingForTaskStudentAndVersion($task_id, $student_id, $version) {
      return $this->model
         -> where('task_id', '=', $task_id)
         -> where('student_id', '=', $student_id)
         -> where('version', '=', $version)
         -> get();
   }
}
?>