<?php
// ------------------------ (C) mgr inż. Bartłomiej Trojnar; 30.06.2023 ------------------------ //
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
        $grades = $gradeRepo -> getGradesInYear($group->end);
        $i=0;
        $gradesSelected[0] = "";
        foreach($group -> grades as $gradeSel)  $gradesSelected[++$i] = $gradeSel->grade_id;
        if($i) $gradeSelectedYear = $group->grades[0]->grade->year_of_beginning; else $gradeSelectedYear=0;
        $year = $schoolYearRepo -> getYear();
        $css = 'group/gradesList.css';
        return view('groupGrade.gradesList', ["group_id"=>$id, "grades"=>$grades, "gradesSelected"=>$gradesSelected, "year"=>$year, "gradeSelectedYear"=>$gradeSelectedYear, "version"=>$version, "css"=>$css]);
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

    public function removeGrade(Request $request) {
        GroupGrade::where('group_id', '=', $request->group_id)
            -> where('grade_id', '=', $request->grade_id) -> delete();
        return;
    }

    public function destroy($id, GroupGrade $groupGrade) {
        $groupGrade = $groupGrade -> find($id);
        $groupGrade -> delete();
        return;
    }

    public function changeName(Request $request) {
        $name = $request->name;
        if($request->name=="")  $name=NULL;
        GroupGrade::where('group_id', '=', $request->group_id)
            -> where('grade_id', '=', $request->grade_id) -> update(['name'=>$name]);
    }
}
