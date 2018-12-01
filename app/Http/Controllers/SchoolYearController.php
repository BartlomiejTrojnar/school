<?php
namespace App\Http\Controllers;
use App\Models\SchoolYear;
use App\Repositories\SchoolYearRepository;

use App\Models\Grade;
use App\Models\Student;
//use App\Models\Group;
//use App\Models\Teacher;
use Illuminate\Http\Request;

class SchoolYearController extends Controller
{
    public function index(SchoolYearRepository $schoolYearRepo)
    {
        $orderBy[0] = 'id';
        $orderBy[1] = 'desc';
        $schoolYears = $schoolYearRepo->getPaginate($orderBy);
        return view('schoolYear.index', ["schoolYears"=>$schoolYears]);
    }

    public function create()
    {
        return view('schoolYear.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
          'date_start' => 'required|date_format:"Y-m-d"',
          'date_end' => 'required|date_format:"Y-m-d"',
          'date_of_classification_of_the_last_grade' => 'nullable|date',
          'date_of_graduation_of_the_last_grade' => 'nullable|date',
          'date_of_classification' => 'nullable|date',
          'date_of_graduation' => 'nullable|date',
        ]);

        $sy = new SchoolYear;
        $sy->date_start = $request->date_start;
        $sy->date_end = $request->date_end;
        $sy->date_of_classification_of_the_last_grade = $request->date_of_classification_of_the_last_grade;
        $sy->date_of_graduation_of_the_last_grade = $request->date_of_graduation_of_the_last_grade;
        $sy->date_of_classification = $request->date_of_classification;
        $sy->date_of_graduation = $request->date_of_graduation;
        $sy->save();

        return redirect($request->history_view);
    }

    public function change($id)
    {
        session()->put('schoolYearSelected', $id);
        return redirect( $_SERVER['HTTP_REFERER'] );
    }

    public function show($id, $view='', SchoolYearRepository $schoolYearRepo)
    {
        if(empty(session()->get('schoolYearView')))  session()->put('schoolYearView', 'showInfo');
        if($view)  session()->put('schoolYearView', $view);
        $schoolYear = $schoolYearRepo -> find($id);
        $previous = $schoolYearRepo -> PreviousRecordId($id);
        $next = $schoolYearRepo -> NextRecordId($id);

        switch(session()->get('schoolYearView')) {
          case 'showInfo':
              return view('schoolYear.show', ["schoolYear"=>$schoolYear, "previous"=>$previous, "next"=>$next])
                  -> nest('subView', 'schoolYear.showInfo', ["schoolYear"=>$schoolYear]);
              exit;
          break;
          case 'showClasses':
              $subTitle = "klasy w roku szkolnym";
              $grades = Grade::where('year_of_beginning', '<', $schoolYear->date_end)
                            -> where('year_of_graduation', '>', $schoolYear->date_start) -> paginate();
              return view('schoolYear.show', ["schoolYear"=>$schoolYear, "previous"=>$previous, "next"=>$next])
                  -> nest('subView', 'grade.table', ["schoolYear"=>$schoolYear, "subTitle"=>$subTitle, "grades"=>$grades]);
              exit;
          break;
          case 'showStudents':
              $subTitle = "uczniowie w roku szkolnym";
              $students = Student::join('student_classes', 'students.id', '=', 'student_classes.student_id')
                  -> select('students.*')
                  -> where('date_start', '>=', $schoolYear->date_start)
                  -> where('date_end', '<=', $schoolYear->date_end)
                  -> get();
              return view('schoolYear.show', ["schoolYear"=>$schoolYear, "previous"=>$previous, "next"=>$next])
                  -> nest('subView', 'student.table', ["schoolYear"=>$schoolYear, "subTitle"=>$subTitle, "students"=>$students, "links"=>false]);
              exit;
          break;
/*
          case 'showTeachers':
             $teachers = $this->getTeachers($id);
             return view('schoolYear.showTeachers', ["schoolYear"=>$schoolYear, "teachers"=>$teachers, "previous"=>$previous, "next"=>$next]);
             exit;
          break;
          case 'showGroups':
              $groups = $this->getGroups($schoolYear);
              return view('schoolYear.showGroups', ["schoolYear"=>$schoolYear, "groups"=>$groups, "previous"=>$previous, "next"=>$next]);
              exit;
          break;
          case 'showTextbooks':
              $textbookChoices = $schoolYearRepo -> find($id) -> textbookChoices;
              return view('schoolYear.showTextbooks', ["schoolYear"=>$schoolYear, "textbookChoices"=>$textbookChoices, "previous"=>$previous, "next"=>$next]);
              exit;
          break;
*/
          case 'change':
              session()->put('schoolYearSelected', $id);
              return redirect( $_SERVER['HTTP_REFERER'] );
          break;
          default:
              printf('<p style="background: #bb0; color: #f00; font-size: x-large; text-align: center; border: 3px solid red; padding: 5px;">Widok %s nieznany</p>', session()->get('schoolYearView'));
              exit;
          break;
        }
    }
/*
    private function getTeachers($id)
    {
        $teachers = Teacher :: where('first_year_id', '<=', $id)
            -> where(function($q) use ($id) {
                $q -> where('last_year_id', '>', $id) -> orwhere('last_year_id', NULL);
            })
            -> orderBy(session()->get("TeacherOrderBy[0]"), session()->get("TeacherOrderBy[1]"))
            -> orderBy(session()->get("TeacherOrderBy[2]"), session()->get("TeacherOrderBy[3]"))
            -> orderBy(session()->get("TeacherOrderBy[4]"), session()->get("TeacherOrderBy[5]"))
            -> paginate(15);
        return $teachers;
    }
/*
    private function getGroups($schoolYear)
    {
        $groups = Group :: where('date_start', '>=', $schoolYear->date_start)
            -> where('date_end', '<=', $schoolYear->date_end)
            -> orderBy(session()->get("GroupOrderBy[0]"), session()->get("GroupOrderBy[1]"))
            -> orderBy(session()->get("GroupOrderBy[2]"), session()->get("GroupOrderBy[3]"))
            -> orderBy(session()->get("GroupOrderBy[4]"), session()->get("GroupOrderBy[5]"))
            -> paginate(15);
        return $groups;
    }


*/
    public function edit(SchoolYear $rok_szkolny)
    {
        return view('schoolYear.edit', ["schoolYear"=>$rok_szkolny]);
    }

    public function update(Request $request, SchoolYear $rok_szkolny)
    {
        $sy = $rok_szkolny;
        $this->validate($request, [
          'date_start' => 'required|date_format:"Y-m-d"',
          'date_end' => 'required|date_format:"Y-m-d"',
          'date_of_classification_of_the_last_grade' => 'nullable|date',
          'date_of_graduation_of_the_last_grade' => 'nullable|date',
          'date_of_classification' => 'nullable|date',
          'date_of_graduation' => 'nullable|date',
        ]);

        $sy->date_start = $request->date_start;
        $sy->date_end = $request->date_end;
        $sy->date_of_classification_of_the_last_grade = $request->date_of_classification_of_the_last_grade;
        $sy->date_of_graduation_of_the_last_grade = $request->date_of_graduation_of_the_last_grade;
        $sy->date_of_classification = $request->date_of_classification;
        $sy->date_of_graduation = $request->date_of_graduation;
        $sy->save();

        return redirect($request->history_view);
    }

    public function destroy(SchoolYear $rok_szkolny)
    {
        $rok_szkolny->delete();
        return redirect( $_SERVER['HTTP_REFERER'] );
    }
}
