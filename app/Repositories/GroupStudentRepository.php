<?php
// ------------------------ (C) mgr inż. Bartłomiej Trojnar; 03.07.2023 ------------------------ //
namespace App\Repositories;
use App\Models\Group;
use App\Models\GroupStudent;

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

   public function getStudentsFromGroup($group_id) {  //pobranie tylko identyfikatorów uczniów ze wskazanej grupy
      $records = $this->model -> where('group_students.group_id', '=', $group_id) -> get();
      return $records;
   }

   public function getStudentGroups($student_id) {    // pobranie wszystkich grup ucznia
      return $this->model -> select('group_students.*')
         -> leftjoin('groups', 'groups.id', '=', 'group_students.group_id')
         -> where('student_id', '=', $student_id) -> orderby('groups.subject_id') -> orderby('group_students.start') -> get();
   }

   public function getOtherGroupsInGrade($student, $grade, $date) {  // pobranie grup z klasy ucznia, do których nigdy nie należał
      $groupRepo = new GroupRepository(new Group);
      $groups = $groupRepo -> getGradeGroups($grade);
      $records = [];
      foreach($groups as $group)    if( !$this -> checkStudentGroup($group->id, $student, $date) ) $records[] = $group;
      return $records;
   }
}
?>