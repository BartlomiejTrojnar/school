<?php
// ------------------------ (C) mgr inż. Bartłomiej Trojnar; 15.03.2022 ------------------------ //
namespace App\Repositories;
use App\Models\Grade;
use Illuminate\Support\Facades\DB;

class GradeRepository extends BaseRepository {
   public function __construct(Grade $model)  { $this->model = $model; }

   public function getAllSorted() {
      return $this->model
         -> orderBy( session() -> get('GradeOrderBy[0]'), session() -> get('GradeOrderBy[1]') )
         -> orderBy( session() -> get('GradeOrderBy[2]'), session() -> get('GradeOrderBy[3]') )
         -> get();
   }

   public function getAllSortedAndPaginate() {
      return $this->model
         -> orderBy( session() -> get('GradeOrderBy[0]'), session() -> get('GradeOrderBy[1]') )
         -> orderBy( session() -> get('GradeOrderBy[2]'), session() -> get('GradeOrderBy[3]') )
         -> paginate(20);
   }

   public function getFilteredAndSorted($year=0, $school_id=0) {
      $records = $this->model;
      if($year) $records = $records
         -> where('year_of_beginning', '<', $year)
         -> where('year_of_graduation', '>=', $year);
      if($school_id) $records = $records -> where('school_id', '=', $school_id);
      $records = $records
         -> orderBy( session() -> get('GradeOrderBy[0]'), session() -> get('GradeOrderBy[1]') )
         -> orderBy( session() -> get('GradeOrderBy[2]'), session() -> get('GradeOrderBy[3]') )
         -> get();
      return $records;
   }

   public function getFilteredAndSortedAndPaginate($year=0, $school_id=0) {
      $records = $this->model;
      if($year) $records = $records
         -> where('year_of_beginning', '<', $year)
         -> where('year_of_graduation', '>=', $year);
      if($school_id) $records = $records -> where('school_id', '=', $school_id);
      $records = $records
         -> orderBy( session() -> get('GradeOrderBy[0]'), session() -> get('GradeOrderBy[1]') )
         -> orderBy( session() -> get('GradeOrderBy[2]'), session() -> get('GradeOrderBy[3]') )
         -> paginate(20);
      return $records;
   }

   public function sortAndPaginateRecords($records) {
      return $records
         -> orderBy( session() -> get('GradeOrderBy[0]'), session() -> get('GradeOrderBy[1]') )
         -> orderBy( session() -> get('GradeOrderBy[2]'), session() -> get('GradeOrderBy[3]') )
         -> paginate(20);
   }

   public function countGradesInYear($year)  { return $this -> getGradesInYear($year) -> count(); }

   public function getGradesInYear($year) {
      return $this->model
         -> where('year_of_beginning', '<=', $year) -> where('year_of_graduation', '>=', $year)
         -> orderBy( 'year_of_beginning', 'desc' ) -> orderBy( 'year_of_graduation', 'desc' ) -> orderBy( 'symbol', 'asc' )
         -> get();
   }

   public function getGroupGrades($group_id) {
      return $this->model
         -> join('group_grades', 'grades.id', '=', 'group_grades.grade_id')
         -> where('group_grades.group_id', '=', $group_id)
         -> orderBy( 'grades.year_of_beginning', 'asc' )
         -> orderBy( 'grades.symbol', 'asc' )
         -> get();
   }

   public function getTeacherGrades($teacher_id, $year=0, $school_id=0) {
      $records = $this->model
         -> join('group_grades', 'grades.id', '=', 'group_grades.grade_id')
         -> join('group_teachers', 'group_teachers.group_id', '=', 'group_grades.group_id')
         -> where('teacher_id', '=', $teacher_id);
      if($year) $records = $records
         -> where('year_of_beginning', '<', $year)
         -> where('year_of_graduation', '>=', $year);
      if($school_id) $records = $records -> where('school_id', '=', $school_id);
      $records = $records
         -> orderBy( session() -> get('GradeOrderBy[0]'), session() -> get('GradeOrderBy[1]') )
         -> orderBy( session() -> get('GradeOrderBy[2]'), session() -> get('GradeOrderBy[3]') )
         -> get();
      return $records;
   }
}
?>