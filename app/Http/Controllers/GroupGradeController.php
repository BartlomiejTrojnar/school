<?php
// ------------------------ (C) mgr inż. Bartłomiej Trojnar; 07.01.2022 ------------------------ //
namespace App\Http\Controllers;
use App\Models\GroupGrade;
use App\Repositories\GradeRepository;
use App\Repositories\GroupRepository;
use App\Repositories\SchoolYearRepository;
use Illuminate\Http\Request;

class GroupGradeController extends Controller
{
    public function gradesList($id, $version="forIndex", GradeRepository $gradeRepo, GroupRepository $groupRepo, SchoolYearRepository $schoolYearRepo) {
        session() -> put('groupSelected', $id);
        $group = $groupRepo -> find( $id );
        $grades = $gradeRepo -> getGradesInYear($group->date_end);
        $schoolYear = $schoolYearRepo -> find( session()->get('schoolYearSelected') );
        $i=0;
        $gradesSelected[0] = "";
        foreach($group -> grades as $gradeSel)  $gradesSelected[++$i] = $gradeSel->grade_id;
        if($i) $gradeSelectedYear = $group->grades[0]->grade->year_of_graduation; else $gradeSelectedYear=0;

        return view('groupGrade.gradesList', ["group_id"=>$id, "grades"=>$grades, "gradesSelected"=>$gradesSelected, "schoolYear"=>$schoolYear, "gradeSelectedYear"=>$gradeSelectedYear, "version"=>$version]);
    }

    public function store(Request $request) {
        $this -> validate($request, [
          'group_id' => 'required',
          'grade_id' => 'required',
        ]);

        $groupGrade = new GroupGrade;
        $groupGrade->group_id = $request->group_id;
        $groupGrade->grade_id = $request->grade_id;
        $groupGrade -> save();
        print_r($groupGrade->id);
    }

    public function removeGrade(Request $request, GroupGrade $groupGrade) {
        GroupGrade::where('group_id', '=', $request->group_id)
            -> where('grade_id', '=', $request->grade_id) -> delete();
        return;
    }

    public function destroy($id, GroupGrade $groupGrade) {
        $groupGrade = $groupGrade -> find($id);
        $groupGrade -> delete();
        return;
    }
}
