<?php
namespace App\Repositories;
use App\Models\SchoolYear;

class SchoolYearRepository extends BaseRepository {
    public function __construct(SchoolYear $model) {
        $this->model = $model;
    }

    public function getDatesOfSchoolYear($date) {
        $proposedDates['dateOfSession'] = session()->get('dateSession');
        $schoolYear = $this->model->where('date_start', '<=', $date)->where('date_end', '>=', $date)->get();
        $proposedDates['dateOfStartSchoolYear'] = $schoolYear[0]->date_start;
        $proposedDates['dateOfGraduationOfTheLastGrade'] = $schoolYear[0]->date_of_graduation_of_the_last_grade;
        $proposedDates['dateOfGraduationSchoolYear'] = $schoolYear[0]->date_of_graduation;
        return $proposedDates;
    }
}
?>