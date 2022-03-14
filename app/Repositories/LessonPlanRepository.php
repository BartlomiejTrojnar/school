<?php
// ------------------------ (C) mgr inż. Bartłomiej Trojnar; 04.03.2022 ------------------------ //
namespace App\Repositories;
use App\Models\LessonPlan;

class LessonPlanRepository extends BaseRepository {
   public function __construct(LessonPlan $model)  { $this->model = $model; }

   public function findClassroomLesson($classroom_id, $lessonHour_id, $dateView) {
      $records = $this->model
         -> where('classroom_id', '=', $classroom_id)
         -> where('lesson_hour_id', '=', $lessonHour_id)
         -> where('start', '<=', $dateView)
         -> where('end', '>=', $dateView)
         -> get();
      return $records;
   }

   public function findLessonsWithoutClassroom($lessonHour_id, $dateView) {
      $records = $this->model
         -> whereNull('classroom_id')
         -> where('lesson_hour_id', '=', $lessonHour_id)
         -> where('start', '<=', $dateView)
         -> where('end', '>=', $dateView)
         -> get();
      return $records;
   }

   public function findClassroomLessonsForHour($classroom_id, $lessonHour_id, $dateView) {
      $records = $this->model
         -> where('classroom_id', '=', $classroom_id)
         -> where('lesson_hour_id', '=', $lessonHour_id)
         -> where('lesson_plans.start', '<=', $dateView)
         -> where('lesson_plans.end', '>=', $dateView)
         -> get();
      return $records;
   }

   public function findGroupLessonForHour($group_id, $lessonHour_id, $dateView) {
      $records = $this->model
         -> where('group_id', '=', $group_id)
         -> where('lesson_hour_id', '=', $lessonHour_id)
         -> where('start', '<=', $dateView)
         -> where('end', '>=', $dateView)
         -> get();
      return $records;
   }

   public function findLessonsForHour($lessonHour_id, $dateView) {
      $records = $this->model
         -> where('lesson_hour_id', '=', $lessonHour_id)
         -> where('start', '<=', $dateView)
         -> where('end', '>=', $dateView)
         -> get();
      return $records;
   }

   public function countGroupLesson($group_id, $dateView) {
      return $this->model
         -> where('group_id', '=', $group_id)
         -> where('start', '<=', $dateView)
         -> where('end', '>=', $dateView)
         -> count();
   }

   public function getGradeLessons($grade_id) {
      $records = $this->model -> select('lesson_plans.*')
         -> leftjoin('groups', 'lesson_plans.group_id', '=', 'groups.id')
         -> leftjoin('group_grades', 'groups.id', '=', 'group_grades.group_id')
         -> leftjoin('subjects', 'groups.subject_id', '=', 'subjects.id')
         -> where('grade_id', '=', $grade_id)
         -> orderBy( 'subjects.name', 'asc' ) -> orderBy('groups.comments', 'asc')
         -> get();
      return $records;
   }

   public function getGroupLessons($group_id) {
      $records = $this->model -> select('lesson_plans.*')
         -> where('group_id', '=', $group_id)
         -> get();
      return $records;
   }

   public function getStudentLessons($student_id, $dateView='2021-04-06') {
      $records = $this->model -> select('lesson_plans.*')
         -> leftjoin('groups', 'lesson_plans.group_id', '=', 'groups.id')
         -> leftjoin('group_students', 'groups.id', '=', 'group_students.group_id')
         -> where('student_id', '=', $student_id)
         -> where('group_students.start', '<=', $dateView)
         -> where('group_students.end', '>=', $dateView)
         -> get();
      return $records;
   }

   public function getClassroomLessons($classroom_id) {
      $records = $this->model
         -> where('classroom_id', '=', $classroom_id)
         -> get();
      return $records;
   }

   public function getTeacherLessons($teacher_id) {
      $records = $this->model -> select('lesson_plans.*')
         -> leftjoin('groups', 'lesson_plans.group_id', '=', 'groups.id')
         -> leftjoin('group_teachers', 'groups.id', '=', 'group_teachers.group_id')
         -> where('teacher_id', '=', $teacher_id)
         -> get();
      return $records;
   }
}
?>