<?php
namespace App\Http\Controllers;
use App\Models\Teacher;
use App\Repositories\TeacherRepository;

use App\Models\TaughtSubject;
use App\Repositories\ClassroomRepository;
use App\Repositories\SchoolYearRepository;

//use App\Models\Group;
//use App\Models\LessonHour;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function index(TeacherRepository $teacherRepo)
    {
        for($i=0; $i<6; $i++)
          $orderBy[$i] = session()->get("TeacherOrderBy[$i]");

        $teachers = $teacherRepo->getPaginate($orderBy);
        return view('teacher.index', ["teachers"=>$teachers]);
    }

    public function orderBy($column)
    {
        if(session()->get('TeacherOrderBy[0]') == $column)
          if(session()->get('TeacherOrderBy[1]') == 'desc')
            session()->put('TeacherOrderBy[1]', 'asc');
          else
            session()->put('TeacherOrderBy[1]', 'desc');
        else
        {
          session()->put('TeacherOrderBy[4]', session()->get('TeacherOrderBy[2]'));
          session()->put('TeacherOrderBy[2]', session()->get('TeacherOrderBy[0]'));
          session()->put('TeacherOrderBy[0]', $column);
          session()->put('TeacherOrderBy[5]', session()->get('TeacherOrderBy[3]'));
          session()->put('TeacherOrderBy[3]', session()->get('TeacherOrderBy[1]'));
          session()->put('TeacherOrderBy[1]', 'asc');
        }

        return redirect( $_SERVER['HTTP_REFERER'] );
    }

    public function create(ClassroomRepository $classroomRepo, SchoolYearRepository $schoolYearRepo)
    {
        $classrooms = $classroomRepo->getAll();
        $schoolYears = $schoolYearRepo->getAll();
        return view('teacher.create')
             ->nest('classroomSelectField', 'classroom.selectField', ["classrooms"=>$classrooms, "selectedClassroom"=>0])
             ->nest('firstYearSelectField', 'schoolYear.selectField', ["schoolYears"=>$schoolYears, "selectedSchoolYear"=>0, "name"=>'first_year_id'])
             ->nest('lastYearSelectField', 'schoolYear.selectField', ["schoolYears"=>$schoolYears, "selectedSchoolYear"=>0, "name"=>'last_year_id']);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
          'first_name' => 'max:16',
          'last_name' => 'required|max:18',
          'family_name' => 'max:15',
          'short' => 'max:2',
          'degree' => 'max:10',
          'order' => 'required|integer|between:0,20',
        ]);

        $teacher = new Teacher;
        $teacher->first_name = $request->first_name;
        $teacher->last_name = $request->last_name;
        $teacher->family_name = $request->family_name;
        $teacher->short = $request->short;
        $teacher->degree = $request->degree;
        $teacher->classroom_id = $request->classroom_id;
        $teacher->first_year_id = $request->first_year_id;
        $teacher->last_year_id = $request->last_year_id;
        if($teacher->last_year_id == 0) $teacher->last_year_id = NULL;
        $teacher->order = $request->order;
        $teacher->save();

        return redirect($request->history_view);
    }

    public function show($id, $view='', TeacherRepository $teacherRepo)
    {
        if(empty(session()->get('teacherView')))  session()->put('teacherView', 'showInfo');
        if($view)  session()->put('teacherView', $view);
        $teacher = $teacherRepo -> find($id);
        $this->previous = $teacherRepo -> PreviousRecordId($id);
        $this->next = $teacherRepo -> NextRecordId($id);

        switch(session()->get('teacherView')) {
/*
          case 'showInfo':
              return view('teacher.showInfo', ["teacher"=>$teacher, "previous"=>$this->previous, "next"=>$this->next]);
              exit;
          break;
*/
          case 'showSubjects':
              //$taughtSubjects = TaughtSubject::where('teacher_id', $id)->get();
              $taughtSubjects = $teacher -> subjects;
              $nonTaughtSubjects = TaughtSubject::nonTaughtSubjects($taughtSubjects);
              return view('teacher.showSubjects', ["teacher"=>$teacher, "previous"=>$this->previous, "next"=>$this->next,
                  "taughtSubjects"=>$taughtSubjects, "nonTaughtSubjects"=>$nonTaughtSubjects]);
              exit;
          break;
/*
          case 'showGroups':
              return $this -> showGroups($teacher);
              exit;
          break;
          case 'showLessonPlans':
              $lessonHours = LessonHour::where('day', 'poniedziałek') -> get();
              return view('teacher.showLessonPlans', ["teacher"=>$teacher, "lessonHours"=>$lessonHours, "previous"=>$this->previous, "next"=>$this->next]);
              exit;
          break;
*/
          default:
              printf('<p style="background: #bb0; color: #f00; font-size: x-large; text-align: center; border: 3px solid red; padding: 5px;">Widok %s nieznany</p>', session()->get('teacherView'));
              exit;
          break;
        }
    }
/*
    public function showGroups($teacher)
    {
        for($i=0; $i<6; $i++)
          $orderBy[$i] = session()->get("GroupOrderBy[$i]");
        $groups = Group::all();
        return view('teacher.showGroups', ["teacher"=>$teacher, "groups"=>$groups, "previous"=>$this->previous, "next"=>$this->next]);
    }

*/
    public function edit(Teacher $nauczyciel, ClassroomRepository $classroomRepo, SchoolYearRepository $schoolYearRepo)
    {
        $classrooms = $classroomRepo->getAll();
        $schoolYears = $schoolYearRepo->getAll();
        return view('teacher.edit', ["teacher"=>$nauczyciel])
             ->nest('classroomSelectField', 'classroom.selectField', ["classrooms"=>$classrooms, "selectedClassroom"=>$nauczyciel->classroom_id])
             ->nest('firstYearSelectField', 'schoolYear.selectField', ["schoolYears"=>$schoolYears, "selectedSchoolYear"=>$nauczyciel->first_year_id, "name"=>'first_year_id'])
             ->nest('lastYearSelectField', 'schoolYear.selectField', ["schoolYears"=>$schoolYears, "selectedSchoolYear"=>$nauczyciel->last_year_id, "name"=>'last_year_id']);
    }

    public function update(Request $request, Teacher $nauczyciel)
    {
        $this->validate($request, [
          'first_name' => 'max:16',
          'last_name' => 'required|max:18',
          'family_name' => 'max:15',
          'short' => 'max:2',
          'degree' => 'max:10',
          'order' => 'required|integer|between:0,20',
        ]);

        $nauczyciel->first_name = $request->first_name;
        $nauczyciel->last_name = $request->last_name;
        $nauczyciel->family_name = $request->family_name;
        $nauczyciel->short = $request->short;
        $nauczyciel->degree = $request->degree;
        $nauczyciel->classroom_id = $request->classroom_id;
        $nauczyciel->first_year_id = $request->first_year;
        $nauczyciel->last_year_id = $request->last_year;
        $nauczyciel->order = $request->order;
        $nauczyciel->save();

        return redirect($request->history_view);
    }

    public function destroy(Teacher $nauczyciel)
    {
        $nauczyciel->delete();
        return redirect( $_SERVER['HTTP_REFERER'] );
    }
}
