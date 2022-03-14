<?php
namespace App\Repositories;
use App\Models\SchoolYear;

class SchoolYearRepository extends BaseRepository {
   public function __construct(SchoolYear $model)  { $this->model = $model; }
   public function getAllSorted()  { return $this->model -> orderBy( 'date_start', 'desc' ) -> get(); }
   public function getPaginateSorted()  { return $this->model -> orderBy( 'date_start', 'desc' ) -> paginate(20); }

   public function getDatesOfSchoolYear($date) {
      $schoolYear = $this->model->where('date_start', '<=', $date)->where('date_end', '>=', $date)->get();
      if( !count($schoolYear) ) return;
      $proposedDates['dateOfStartSchoolYear'] = $schoolYear[0]->date_start;
      $proposedDates['dateOfGraduationOfTheLastGrade'] = $schoolYear[0]->date_of_graduation_of_the_last_grade;
      $proposedDates['dateOfGraduationSchoolYear'] = $schoolYear[0]->date_of_graduation;
      $proposedDates['dateOfEndSchoolYear'] = $schoolYear[0]->date_end;
      return $proposedDates;
   }

   public function getSchoolYearIdForDate($date) {
      $schoolYear = $this->model->where('date_start', '<=', $date)->where('date_end', '>=', $date)->get();
      if(isset($schoolYear[0]->id))  return $schoolYear[0]->id;
      return 0;
   }
}
?>