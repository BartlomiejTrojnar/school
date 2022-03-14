<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\GradeRepository;
use App\Repositories\GroupRepository;
use App\Repositories\StudentRepository;
use App\Repositories\SchoolYearRepository;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class GroupCompareController extends Controller
{
    public function compare(GradeRepository $gradeRepo, SchoolYearRepository $schoolYearRepo, GroupRepository $groupRepo)  {
        $schoolYears = $schoolYearRepo -> getAllSorted();
        $schoolYearSelected = session()->get('schoolYearSelected');
        $schoolYearSelectField = view('schoolYear.selectField', ["name"=>"schoolYearChoice1", "schoolYears"=>$schoolYears, "schoolYearSelected"=>$schoolYearSelected]);

        $year = session()->get('schoolYearSelected');
        if( !empty($year) )  {
            $schoolYear = $schoolYearRepo -> find( $year );
            $year = substr($schoolYear->date_end, 0, 4);
        }
        $grades = $gradeRepo -> getFilteredAndSorted($year, 0);
        $gradeSelected = session()->get('gradeSelected');
        $gradeSelectField = view('grade.selectField', ["name"=>"grade_id", "grades"=>$grades, "gradeSelected"=>$gradeSelected, "year"=>$year]);

        $dateView = session()->get('dateView');
        $groups = $groupRepo -> getGroups($dateView, $dateView, 253);
        $gradeSelectFieldForGroup1 = view('grade.selectField', ["name"=>"gradesForGroup1", "grades"=>$grades, "gradeSelected"=>$gradeSelected, "year"=>$year]);
        $gradeSelectFieldForGroup2 = view('grade.selectField', ["name"=>"gradesForGroup2", "grades"=>$grades, "gradeSelected"=>$gradeSelected, "year"=>$year]);
        $gradeSelectFieldForGroup3 = view('grade.selectField', ["name"=>"gradesForGroup3", "grades"=>$grades, "gradeSelected"=>$gradeSelected, "year"=>$year]);
        $gradeSelectFieldForGroup4 = view('grade.selectField', ["name"=>"gradesForGroup4", "grades"=>$grades, "gradeSelected"=>$gradeSelected, "year"=>$year]);
        $groupSelectField1 = view('group.selectField', ["name"=>"groupChoice1", "groups"=>$groups, "groupSelected"=>0]);
        $groupSelectField2 = view('group.selectField', ["name"=>"groupChoice2", "groups"=>$groups, "groupSelected"=>0]);
        $groupSelectField3 = view('group.selectField', ["name"=>"groupChoice3", "groups"=>$groups, "groupSelected"=>0]);
        $groupSelectField4 = view('group.selectField', ["name"=>"groupChoice4", "groups"=>$groups, "groupSelected"=>0]);

        return view('groupCompare.index', ["schoolYearSelectField"=>$schoolYearSelectField, "gradeSelectField"=>$gradeSelectField,
            "gradeSelectFieldForGroup1"=>$gradeSelectFieldForGroup1, "groupSelectField1"=>$groupSelectField1,
            "gradeSelectFieldForGroup2"=>$gradeSelectFieldForGroup2, "groupSelectField2"=>$groupSelectField2,
            "gradeSelectFieldForGroup3"=>$gradeSelectFieldForGroup3, "groupSelectField3"=>$groupSelectField3,
            "gradeSelectFieldForGroup4"=>$gradeSelectFieldForGroup4, "groupSelectField4"=>$groupSelectField4]);
    }

    public function gradeChoice(Request $request, StudentRepository $studentRepo)  {
        $grades[] = $request->grade_id;
        $students = $studentRepo -> getStudentsFromGrades($grades, $request->dateView);
        if(empty($students)) $students=0;
        return view('groupCompare.studentsToVerify', ["students"=>$students, "dateView"=>$request->dateView]);
    }

    public function displayGroup(Request $request, StudentRepository $studentRepo)  {
        $students = $studentRepo -> getStudentsFromGroup($request->group_id, $request->dateView);
        if(empty($students)) $students=0;
        return view('groupCompare.listOfGroupStudents', ["students"=>$students]);
    }
}
