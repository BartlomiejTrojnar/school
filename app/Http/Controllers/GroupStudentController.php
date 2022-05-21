<?php
// ------------------------ (C) mgr inż. Bartłomiej Trojnar; 21.05.2022 ------------------------ //
namespace App\Http\Controllers;
use App\Models\GroupStudent;
use App\Repositories\GroupStudentRepository;

use App\Models\Group;
use App\Models\SchoolYear;
use App\Repositories\GroupRepository;
use App\Repositories\SchoolYearRepository;
use App\Repositories\StudentGradeRepository;
use Illuminate\Http\Request;

class GroupStudentController extends Controller
{
    public function orderBy($column) {
        if(session()->get('GroupStudentOrderBy[0]') == $column)
            if(session()->get('GroupStudentOrderBy[1]') == 'desc') session()->put('GroupStudentOrderBy[1]', 'asc');
            else session()->put('GroupStudentOrderBy[1]', 'desc');
        else {
            session()->put('GroupStudentOrderBy[4]', session()->get('GroupStudentOrderBy[2]'));
            session()->put('GroupStudentOrderBy[2]', session()->get('GroupStudentOrderBy[0]'));
            session()->put('GroupStudentOrderBy[0]', $column);
            session()->put('GroupStudentOrderBy[5]', session()->get('GroupStudentOrderBy[3]'));
            session()->put('GroupStudentOrderBy[3]', session()->get('GroupStudentOrderBy[1]'));
            session()->put('GroupStudentOrderBy[1]', 'asc');
        }
        return redirect( $_SERVER['HTTP_REFERER'] );
    }

    public function getGroupStudents(Request $request, GroupStudentRepository $groupStudentRepo, GroupRepository $groupRepo, SchoolYearRepository $schoolYearRepo) {
        $groupStudents = $groupStudentRepo -> getGroupStudents($request->group_id, $request->dateView);
        $schoolYear = $schoolYearRepo -> getSchoolYearIdForDate($request->dateView);
        $group = $groupRepo -> find( session()->get('groupSelected') );
        return view('groupStudent.listForGroup', ["groupStudents"=>$groupStudents, "schoolYear"=>$schoolYear, "dateView"=>$request->dateView, "group"=>$group]);
    }

    public function getAnotherTimeGroupStudents(Request $request, GroupStudentRepository $groupStudentRepo) {
        $groupStudentsInOtherTime = $groupStudentRepo -> getGroupStudentsInOtherTime($request->group_id, $request->dateView);
        $schoolYearRepo = new SchoolYearRepository(new SchoolYear);
        $schoolYear = $schoolYearRepo -> getSchoolYearIdForDate($request->dateView);
        return view('groupStudent.listGroupStudentsInOtherTime', ["groupStudentsInOtherTime"=>$groupStudentsInOtherTime, "schoolYear"=>$schoolYear, "dateView"=>$request->dateView]);
    }

    public function getOutsideGroupStudentsList(Request $request, Group $group, GroupStudentRepository $groupStudentRepo, SchoolYearRepository $schoolYearRepo) {
        $group = $group -> find($request->group_id);
        $outsideGroupStudents = $groupStudentRepo -> getOutsideGroupStudents($group, $request->dateView);

        $schoolYearRepo = new SchoolYearRepository(new SchoolYear);
        $schoolYear = $schoolYearRepo -> getSchoolYearIdForDate($request->dateView);

        return view('groupStudent.listOutsideGroupStudents', ["outsideGroupStudents"=>$outsideGroupStudents, "dateView"=>$request->dateView, "schoolYear"=>$schoolYear]);
    }
/*
    public function getStudentGroups(Request $request, GroupStudentRepository $groupStudentRepo, SchoolYearRepository $schoolYearRepo) {
        $groups = $groupStudentRepo -> getStudentGroupsForDate($request->student_id, $request->dateView);
        $year = 0;
        if( !empty(session()->get('schoolYearSelected')) ) {
            $schoolYear = $schoolYearRepo -> find(session()->get('schoolYearSelected'));
            $year = substr($schoolYear->date_end, 0, 4);
        }
        return view('groupStudent.studentList', ["dateView"=>$request->dateView, "groups"=>$groups, "student_id"=>$request->student_id, "year"=>$year]);
    }
    */

    public function addStudent(Request $request) {
        $groupStudent = new GroupStudent;
        $groupStudent->group_id = $request->group_id;
        $groupStudent->student_id = $request->student_id;
        $groupStudent->start  = $request->start;
        $groupStudent->end = $request->end;
        $groupStudent -> save();
        $schoolYear = session()->get('schoolYearSelected');
        return view('groupStudent.liForStudentGroup', ["groupStudent"=>$groupStudent, "dateView"=>$request->start, "schoolYear"=>$schoolYear]);
    }

    public function addManyStudent(Request $request) {
        $n = count($request->students);
        for($i=0; $i<$n; $i++) {
            $groupStudent = new GroupStudent;
            $groupStudent->group_id = $request->group_id;
            $groupStudent->start  = $request->start;
            $groupStudent->end = $request->end;
            $groupStudent->student_id = $request->students[$i];
            $groupStudent -> save();
        }
    }

    public function store(Request $request) {
        $this -> validate($request, [
          'group_id' => 'required',
          'student_id' => 'required',
          'start' => 'required|date',
          'end' => 'required|date',
        ]);

        $groupStudent = new GroupStudent;
        $groupStudent->group_id = $request->group_id;
        $groupStudent->student_id = $request->student_id;
        $groupStudent->start  = $request->start ;
        $groupStudent->end = $request->end;
        $groupStudent -> save();
        return $groupStudent->id;
    }

    public function edit(Request $request, $id, GroupStudent $groupStudent) {
        if($request->version == "forStudent")   return $this->editFormForStudent($id, $groupStudent);
        if($request->version == "forGroup")     return $this->editFormForGroup($id, $groupStudent);
        return $request->version;
    }

    private function editFormForGroup($id, $groupStudent) {
        $groupStudent = $groupStudent -> find($id);
        // pobranie informacji o roku szkolnym (aby wyświetlać rocznik klasy, jeżeli jest wybrany)
        $year = 0;
        if( !empty(session()->get('schoolYearSelected')) ) {
            $schoolYear = SchoolYear::find(session()->get('schoolYearSelected'));
            $year = substr($schoolYear->date_end, 0, 4);
        }
        
        return view('groupStudent.editFormForGroup', ["groupStudent"=>$groupStudent, "year"=>$year]);
    }

    private function editFormForStudent($id, $groupStudent) {
        $groupStudent = $groupStudent -> find($id);
        // pobranie informacji o roku szkolnym (aby wyświetlać rocznik klasy, jeżeli jest wybrany)
        $year = 0;
        if( !empty(session()->get('schoolYearSelected')) ) {
            $schoolYear = SchoolYear::find(session()->get('schoolYearSelected'));
            $year = substr($schoolYear->date_end, 0, 4);
        }
        
        return view('groupStudent.editFormForStudent', ["groupStudent"=>$groupStudent, "year"=>$year]);
    }

    public function update($id, Request $request, GroupStudent $groupStudent) {
        $this -> validate($request, [
          'group_id' => 'required',
          'student_id' => 'required',
          'start' => 'required|date',
          'end' => 'required|date',
        ]);

        $groupStudent = $groupStudent -> find($id);
        $groupStudent->group_id = $request->group_id;
        $groupStudent->student_id = $request->student_id;
        $groupStudent->start  = $request->start;
        $groupStudent->end = $request->end;
        $groupStudent -> save();
        return $groupStudent->id;
    }

    public function updateEnd($id, Request $request, GroupStudent $groupStudent) {
        $groupStudent = $groupStudent -> find($id);
        $groupStudent->end = $request->end;
        $groupStudent -> save();
        return 1;
    }

    public function deleteForm($id, GroupStudent $groupStudent) {
        $groupStudent = $groupStudent -> find($id);
        return view('groupStudent.deleteForm', ["groupStudent"=>$groupStudent]);
    }

    public function delete($id, GroupStudent $groupStudent) {
        $groupStudent = $groupStudent -> find($id);
        $groupStudent -> delete();
    }

    public function destroy($id, GroupStudent $groupStudent) {
        $groupStudent = $groupStudent -> find($id);
        foreach($groupStudent->ratings as $rating)      $rating -> delete();
        $groupStudent -> delete();
        return $id;
    }

    public function removeYesterday(Request $request, GroupStudent $groupStudent) {
        $dateEnd = $request->dateEnd;
        $studentGroups = $groupStudent -> where('student_id', '=', $request->student_id) -> get();
        foreach($studentGroups as $studentGroup)
            if($studentGroup->start <= $dateEnd && $studentGroup->end > $dateEnd) {
                $studentGroup->end = $dateEnd;
                $studentGroup -> save();
            }
        return 1;
    }

    public function refreshOtherGroupsInStudentsClass(Request $request, StudentGradeRepository $studentGradeRepo, GroupStudentRepository $groupStudentRepo, SchoolYearRepository $schoolYearRepo) {
        $grade_id = $studentGradeRepo -> getActualClassForStudent($request->student_id, $request->dateView);
          if(!$grade_id) return '<p style="color: yellow; background: red; padding: 5px; border-radius: 10px; box-shadow: 2px 2px 10px white;">Brak aktualnej klasy</p>';
        $groups = $groupStudentRepo -> getOtherGroupsInGrade($request->student_id, $grade_id, $request->dateView);
        // pobranie informacji o roku szkolnym (aby wyświetlać rocznik klasy, jeżeli jest wybrany)
        $year = 0;
        if( !empty(session()->get('schoolYearSelected')) ) {
            $schoolYear = $schoolYearRepo -> find(session()->get('schoolYearSelected'));
            $year = substr($schoolYear->date_end, 0, 4);
        }
        return view('groupStudent.otherGroupsInGradeForStudent', ["groups"=>$groups, "year"=>$year, "dateView"=>$request->dateView]);
    }
}
