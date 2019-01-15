<?php
namespace App\Repositories;
use App\Models\StudentClass;

class StudentClassRepository extends BaseRepository {
    public function __construct(StudentClass $model) {
        $this->model = $model;
    }

    public function getLastNumber() {
        return $this->model->all()->last()->number;
    }

    public function getGradeStudents($grade_id) {
        return $this->model -> where('grade_id', '=', $grade_id)
            -> orderBy( session()->get('StudentClassOrderBy[0]'), session()->get('StudentClassOrderBy[1]') )
            -> orderBy( session()->get('StudentClassOrderBy[2]'), session()->get('StudentClassOrderBy[3]') )
            -> orderBy( session()->get('StudentClassOrderBy[4]'), session()->get('StudentClassOrderBy[5]') )
            -> get();
    }

    public function getStudentGrades($student_id) {
        return $this->model -> where('student_id', '=', $student_id)
            -> orderBy( session()->get('StudentClassOrderBy[0]'), session()->get('StudentClassOrderBy[1]') )
            -> orderBy( session()->get('StudentClassOrderBy[2]'), session()->get('StudentClassOrderBy[3]') )
            -> orderBy( session()->get('StudentClassOrderBy[4]'), session()->get('StudentClassOrderBy[5]') )
            -> get();
    }
}
?>