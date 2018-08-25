<?php
namespace App\Http\Controllers;
use App\Models\GroupClass;
use App\Repositories\GroupClassRepository;
use App\Repositories\GroupRepository;
use App\Repositories\GradeRepository;
use Illuminate\Http\Request;

class GroupClassController extends Controller
{
    public function index(GroupClassRepository $groupClassRepo)
    {
        $groupClasses = $groupClassRepo->getAll();
        return view('groupClass.index', ["groupClasses"=>$groupClasses]);
    }

    public function create(GroupRepository $groupRepo, GradeRepository $gradeRepo)
    {
        $groups = $groupRepo->getAll();
        $grades = $gradeRepo->getAll();
        return view('groupClass.create')
             ->nest('groupSelectField', 'group.selectField', ["groups"=>$groups, "selectedGroup"=>0])
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
        $groupClass->class_id = $request->grade_id;
        $groupClass->save();

        return redirect($request->history_view);
    }

    public function show(GroupClass $grupa_klasy, GroupClassRepository $groupClassRepo)
    {
        $previous = $groupClassRepo->previousRecordId($grupa_klasy->id);
        $next = $groupClassRepo->nextRecordId($grupa_klasy->id);
        return view('groupClass.show', ["groupClass"=>$grupa_klasy, "previous"=>$previous, "next"=>$next]);
    }

    public function edit(GroupClass $grupa_klasy, GroupRepository $groupRepo, GradeRepository $gradeRepo)
    {
        $groups = $groupRepo->getAll();
        $grades = $gradeRepo->getAll();
        return view('groupClass.edit', ["groupClass"=>$grupa_klasy])
             ->nest('groupSelectField', 'group.selectField', ["groups"=>$groups, "selectedGroup"=>$grupa_klasy->group_id])
             ->nest('gradeSelectField', 'grade.selectField', ["grades"=>$grades, "selectedGrade"=>$grupa_klasy->class_id]);
    }

    public function update(Request $request, GroupClass $grupa_klasy)
    {
        $this->validate($request, [
          'group_id' => 'required',
          'grade_id' => 'required',
        ]);

        $grupa_klasy->group_id = $request->group_id;
        $grupa_klasy->class_id = $request->grade_id;
        $grupa_klasy->save();

        return redirect($request->history_view);
    }

    public function destroy(GroupClass $grupa_klasy)
    {
        $grupa_klasy->delete();
        return redirect( $_SERVER['HTTP_REFERER'] );
    }
}
