<?php
// ------------------------ (C) mgr inż. Bartłomiej Trojnar; 30.06.2023 ------------------------ //
namespace App\Repositories;
use App\Models\SchoolYear;

class SchoolYearRepository extends BaseRepository {
   public function __construct(SchoolYear $model)  { $this->model = $model; }
   public function getAllSorted()  { return $this->model -> orderBy( 'date_start', 'desc' ) -> get(); }
   public function getPaginateSorted()  { return $this->model -> orderBy( 'date_start', 'desc' ) -> paginate(20); }

   public function getDatesOfSchoolYear($date) {
      $schoolYear = $this->model -> where('date_start', '<=', $date) -> where('date_end', '>=', $date) -> get();
      if( !count($schoolYear) ) return;
      $proposedDates['dateOfStartSchoolYear'] = $schoolYear[0]->date_start;
      $proposedDates['dateOfGraduationOfTheLastGrade'] = $schoolYear[0]->date_of_graduation_of_the_last_grade;
      $proposedDates['dateOfGraduationSchoolYear'] = $schoolYear[0]->date_of_graduation;
      $proposedDates['dateOfEndSchoolYear'] = $schoolYear[0]->date_end;
      return $proposedDates;
   }

   public function getSchoolYearIdForDate($date) {
      $schoolYear = $this->model -> where('date_start', '<=', $date) -> where('date_end', '>=', $date) -> get();
      if(isset($schoolYear[0]->id))  return $schoolYear[0]->id;
      return 0;
   }

   public function getSchoolYearEnds($year_start, $year_end) {
      $start = $year_start."-09-01";
      $end = $year_end."-09-01";
      $ends = $this->model -> select('date_of_graduation') -> where('date_start', '>', $start) -> where('date_end', '<=', $end) -> get();
      return $ends;
   }

   public function getYear() {
      $year = 0;
      $dateView = session()->get('dateView');
      $year = substr($dateView,0,4);
      if( substr($dateView,5,2)>=8 )  $year++;
      if( !empty(session()->get('schoolYearSelected')) ) {
          $schoolYear = $this -> find(session()->get('schoolYearSelected'));
          $year = substr($schoolYear->date_end,0,4);
      }
      return $year;
   }
}
?>