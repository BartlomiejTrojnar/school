<?php
namespace App\Http\Controllers;
use App\Models\GroupClass;
use App\Repositories\GradeRepository;
use Illuminate\Http\Request;

class GroupClassController extends Controller
{
    public function addGrade($id, GradeRepository $gradeRepo)
    {
        $grades = $gradeRepo->getAll();
        return view('groupClass.addGrade', ["group_id"=>$id])
             ->nest('gradeSelectField', 'grade.selectField', ["grades"=>$grades, "selectedGrade"=>0]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
          'group_id' => 'required',
          'grade_id' => 'required',
        ]);

        $groupClass = new GroupClass;
        $groupClass->group_id = $request->group_id;
        $groupClass->grade_id = $request->grade_id;
        $groupClass->save();

        return redirect($request->history_view);
    }

    public function destroy(GroupClass $grupa_klasy)
    {
        print_r( $grupa_klasy->delete() );
        return;
    }
}
