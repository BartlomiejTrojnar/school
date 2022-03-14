<?php
// ------------------------ (C) mgr inż. Bartłomiej Trojnar; 19.02.2022 ------------------------ //
namespace App\Repositories;
use App\Models\Group;
use App\Models\GroupStudent;
use App\Models\StudentGrade;

class GroupStudentRepository extends BaseRepository {
   public function __construct(GroupStudent $model)  { $this->model = $model; }

   private function checkStudentGroup($group, $student, $date) {
      return $this->model -> where('group_id', '=', $group) -> where('student_id', '=', $student) -> where('start', '<=', $date) -> where('end', '>=', $date) -> count();
   }

   public function getGroupStudents($group_id) {
      $records = $this->model
         -> select('group_students.*', 'students.*', 'group_students.id')
         -> join('students', 'students.id', '=', 'group_students.student_id')
         -> join('student_grades', 'students.id', '=', 'student_grades.student_id')
         -> where('group_students.group_id', '=', $group_id);
      $records = $records
         -> orderBy( session()->get('GroupStudentOrderBy[0]'), session()->get('GroupStudentOrderBy[1]') )
         -> orderby('students.last_name') -> orderby('students.first_name')
         -> distinct() -> get();
      return $records;
   }
/*
   public function getGroupStudentsInOtherTime($group_id, $dateView) {
      $records = $this->model
         -> select('group_students.*', 'students.*', 'group_students.id')
         -> join('students', 'students.id', '=', 'group_students.student_id')
         -> where('group_id', '=', $group_id)
         -> where(function ($query) use ($dateView) { $query -> where('start', '>', $dateView) -> orWhere('end', '<', $dateView); })
         -> orderby('students.last_name') -> orderby('students.first_name')
         -> get();
      return $records;
   }
*/
   public function getOutsideGroupStudents($group, $dateView) {
      $records = [];
      $studentGradeRepo = new StudentGradeRepository(new StudentGrade);

      foreach($group->grades as $groupGrade)
         $grades[] = $groupGrade->grade_id;
      $studentGrades = $studentGradeRepo -> getStudentsFromGradesOrderByLastName($grades, $dateView);
      if(empty($studentGrades)) return [];

      foreach($studentGrades as $studentGrade) {
         if($studentGrade->end < $dateView) continue;
         if($studentGrade->start > $dateView) continue;
         $s = $this->model -> where('group_id', '=', $group->id) -> where('group_students.student_id', '=', $studentGrade->student_id)
            -> where('start', '<=', $dateView) -> where('end', '>=', $dateView) -> get();
         if(!sizeof($s))   $records[] = $studentGrade->student;
      }
      //$records = $records
      //   -> orderby('students.last_name') -> orderby('students.first_name')
      //   -> distinct() -> get();
      return $records;
   }

   public function getStudentGroups($student_id) {    // pobranie wszystkich grup ucznia
      return $this->model -> where('student_id', '=', $student_id) -> get();
   }

   public function getOtherGroupsInGrade($student, $grade, $date) {  // pobranie grup z klasy ucznia, do których nigdy nie należał
      $groupRepo = new GroupRepository(new Group);
      $groups = $groupRepo -> getGradeGroupsFordate($grade, $date);
      $records = [];
      foreach($groups as $group)    if( !$this -> checkStudentGroup($group->id, $student, $date) ) $records[] = $group;
      return $records;
   }
}
?>