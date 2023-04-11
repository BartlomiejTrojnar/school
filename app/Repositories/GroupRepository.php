<?php
// ------------------------ (C) mgr inż. Bartłomiej Trojnar; 11.04.2023 ------------------------ //
namespace App\Repositories;
use App\Models\Group;

class GroupRepository extends BaseRepository {
   public function __construct(Group $model)  { $this->model = $model; }
/*
   public function getGroups($start=0, $end=0, $grade_id=0) {    // pobieranie odfiltrowanych grup
      $records = $this->model;

      if($grade_id)  {
         $records = $records -> select('groups.*')
            -> join('group_grades', 'groups.id', '=', 'group_grades.group_id')
            -> where('group_grades.grade_id', '=', $grade_id);
      }

      if($start)    $records = $records -> where('groups.start', '<=', $end);
      if($end)      $records = $records -> where('groups.end', '>=', $start);

      return $records
         -> orderBy( session()->get('GroupOrderBy[0]'), session()->get('GroupOrderBy[1]') )
         -> orderBy( session()->get('GroupOrderBy[2]'), session()->get('GroupOrderBy[3]') )
         -> orderBy( session()->get('GroupOrderBy[4]'), session()->get('GroupOrderBy[5]') )
         -> get();
   }

   public function getAllSortedAndPaginate() {
      return $this->model
         -> orderBy( session()->get('GroupOrderBy[0]'), session()->get('GroupOrderBy[1]') )
         -> orderBy( session()->get('GroupOrderBy[2]'), session()->get('GroupOrderBy[3]') )
         -> orderBy( session()->get('GroupOrderBy[4]'), session()->get('GroupOrderBy[5]') )
         -> paginate(20);
   }
*/
   public function getFilteredAndSortedAndPaginate($grade_id=0, $subject_id=0, $level=0, $start='', $end='', $teacher_id=0) {
      return $this -> findGroups($grade_id, $subject_id, $level, $start, $end, $teacher_id) -> paginate(20);
   }

   public function getAllFilteredAndSorted($grade_id=0, $subject_id=0, $level=0, $start='', $end='', $teacher_id=0) {
      return $this -> findGroups($grade_id, $subject_id, $level, $start, $end, $teacher_id) -> get();
   }

   public function findGroups($grade_id=0, $subject_id=0, $level=0, $start='', $end='', $teacher_id=0) {
      $records = $this->model -> select('groups.*') -> leftjoin('subjects', 'groups.subject_id', '=', 'subjects.id')
         -> leftjoin('group_grades', 'groups.id', '=', 'group_grades.group_id');
      if($grade_id)
         $records = $records -> where('group_grades.grade_id', '=', $grade_id);
      if($teacher_id)  {
         $records = $records -> select('groups.*')
            -> join('group_teachers', 'groups.id', '=', 'group_teachers.group_id')
            -> where('group_teachers.teacher_id', '=', $teacher_id);
         if($start)    $records = $records -> where('group_teachers.start', '<=', $end);
         if($end)      $records = $records -> where('group_teachers.end', '>=', $start);
      }

      if($subject_id)   $records = $records -> where('subject_id', '=', $subject_id);
      if($level)        $records = $records -> where('level', '=', $level);
      if($start>$end)   $records = $records -> where('groups.id', '=', 0);
      if($end)          $records = $records -> where('groups.end', '>=', $start);
      if($start)        $records = $records -> where('groups.start', '<=', $end);

      $records = $records -> groupBy('groups.id')
         -> orderBy( session()->get('GroupOrderBy[0]'), session()->get('GroupOrderBy[1]') )
         -> orderBy( session()->get('GroupOrderBy[2]'), session()->get('GroupOrderBy[3]') )
         -> orderBy( session()->get('GroupOrderBy[4]'), session()->get('GroupOrderBy[5]') );
      return $records;
   }

   public function countGroupsInYear($start, $end)  { return $this -> getFilteredAndSorted(0,0, $start, $end) -> count(); }

   public function getGradeGroups($grade_id) {
      return $this->model -> select('groups.*') -> join('subjects', 'groups.subject_id', '=', 'subjects.id') -> join('group_grades', 'groups.id', '=', 'group_grades.group_id')
         -> where('group_grades.grade_id', '=', $grade_id)
         -> orderBy( session()->get('GroupOrderBy[0]'), session()->get('GroupOrderBy[1]') )
         -> orderBy( session()->get('GroupOrderBy[2]'), session()->get('GroupOrderBy[3]') )
         -> orderBy( session()->get('GroupOrderBy[4]'), session()->get('GroupOrderBy[5]') )
         -> get();
   }

   public function getTeacherGroupsFordate($teacher_id, $dateView) {
      return $this->model
         -> select('groups.*')
         -> join('group_teachers', 'groups.id', '=', 'group_teachers.group_id')
         -> where('group_teachers.teacher_id', '=', $teacher_id)
         -> where('groups.start', '<=', $dateView)
         -> where('groups.end', '>=', $dateView)
         -> get();
   }
}
?>