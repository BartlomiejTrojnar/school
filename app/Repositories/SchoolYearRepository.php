<?php
namespace App\Repositories;
use App\Models\SchoolYear;

class SchoolYearRepository extends BaseRepository {
    public function __construct(SchoolYear $model) {
        $this->model = $model;
    }

    public function getDatesOfSchoolYear($date) {
        $date='2016-05-20';
        $dates['dateOfSession'] = $date;
        $schoolYear = $this->model->where('date_start', '<=', $date)->where('date_end', '>=', $date)->get();
        print_r($schoolYear); exit;
        //$dates['dateOfStartSchoolYear'] = $schoolYear->date_start;
        return $dates;
    }
}
?>