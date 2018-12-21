<?php
namespace App\Repositories;
use App\Models\TaskRating;
use Illuminate\Support\Facades\DB;

class TaskRatingRepository extends BaseRepository {
    public function __construct(TaskRating $model) {
        $this->model = $model;
    }

    /* pobranie ocen zadania dla wybranego zadania */
    public function getTaskRatings($task_id) {
        return $this->model -> where('task_id', '=', $task_id)
            -> orderBy( session()->get('TaskRatingOrderBy[0]'), session()->get('TaskRatingOrderBy[1]') )
            -> orderBy( session()->get('TaskRatingOrderBy[2]'), session()->get('TaskRatingOrderBy[3]') )
            -> orderBy( session()->get('TaskRatingOrderBy[4]'), session()->get('TaskRatingOrderBy[5]') )
            -> get();
    }

    /* pobranie ocen zadań dla wybranego ucznia */
    public function getStudentTaskRatings($student_id) {
        return $this->model -> where('student_id', '=', $student_id)
            -> orderBy( session()->get('TaskRatingOrderBy[0]'), session()->get('TaskRatingOrderBy[1]') )
            -> orderBy( session()->get('TaskRatingOrderBy[2]'), session()->get('TaskRatingOrderBy[3]') )
            -> orderBy( session()->get('TaskRatingOrderBy[4]'), session()->get('TaskRatingOrderBy[5]') )
            -> get();
    }
}
?>