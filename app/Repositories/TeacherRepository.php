<?php
// ------------------------ (C) mgr inż. Bartłomiej Trojnar; 17.04.2023 ------------------------ //
namespace App\Repositories;
use App\Models\Teacher;
use Illuminate\Support\Facades\DB;

class TeacherRepository extends BaseRepository {
   public function __construct(Teacher $model)  { $this->model = $model; }

   private function findFiltered($schoolYear=0) {
      $records = $this->model;
      if($schoolYear) $records = $records
         -> where('teachers.first_year_id', '<=', session()->get('schoolYearSelected'))
         -> where(function ($query) {
            $query-> where('teachers.last_year_id', '>=', session()->get('schoolYearSelected')) -> orWhere('last_year_id', NULL);
         });
      return $records
         -> orderBy( session()->get('TeacherOrderBy[0]'), session()->get('TeacherOrderBy[1]') )
         -> orderBy( session()->get('TeacherOrderBy[2]'), session()->get('TeacherOrderBy[3]') )
         -> orderBy( session()->get('TeacherOrderBy[4]'), session()->get('TeacherOrderBy[5]') );
   }

   public function getFiltered($schoolYear=0) {     // pobranie odfiltrowanych nauczycieli
      return $this -> findFiltered($schoolYear) -> get();
   }

   public function getFilteredAndPaginate($schoolYear=0) {
      return $this -> findFiltered($schoolYear) -> paginate(20);
   }

   public function countTeachersInYear($schoolYear)  { return $this -> getTeachersInYear($schoolYear) -> count(); }

   public function getTeachersForGroup($group, $schoolYear='') {
      $records = $this->model
         -> select('teachers.*')
         -> join('taught_subjects', 'teachers.id', '=', 'taught_subjects.teacher_id')
         -> where('taught_subjects.subject_id', '=', $group->subject_id);
      if($schoolYear) $records = $records
      //   -> where('teachers.first_year_id', '<=', $schoolYear)
         -> where(function($query) use ($schoolYear) {
            $query -> where('teachers.last_year_id', '>=', $schoolYear) -> orWhere('last_year_id', NULL);
         });
      return $records
         -> orderBy( 'teachers.'.session()->get('TeacherOrderBy[0]'), session()->get('TeacherOrderBy[1]') )
         -> orderBy( 'teachers.'.session()->get('TeacherOrderBy[2]'), session()->get('TeacherOrderBy[3]') )
         -> orderBy( 'teachers.'.session()->get('TeacherOrderBy[4]'), session()->get('TeacherOrderBy[5]') )
         -> get();
   }

   public function getTeachersForGrade($grade_id) {
      return $this->model
         -> select('teachers.id', 'teachers.first_name', 'teachers.last_name', 'teachers.degree', 'teachers.family_name')
         -> join('group_teachers', 'teachers.id', '=', 'group_teachers.teacher_id')
         -> join('groups', 'group_teachers.group_id', '=', 'groups.id')
         -> join('group_grades', 'groups.id', '=', 'group_grades.group_id')
         -> where('group_grades.grade_id', '=', $grade_id)
         -> orderBy( 'teachers.'.session()->get('TeacherOrderBy[0]'), session()->get('TeacherOrderBy[1]') )
         -> orderBy( 'teachers.'.session()->get('TeacherOrderBy[2]'), session()->get('TeacherOrderBy[3]') )
         -> orderBy( 'teachers.'.session()->get('TeacherOrderBy[4]'), session()->get('TeacherOrderBy[5]') )
         -> distinct()
         -> get();
   }
}
?>