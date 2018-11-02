<?php
namespace App\Http\Controllers;
use App\Models\SchoolYear;
use App\Models\Grade;
use App\Repositories\SchoolYearRepository;
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

    public function show($id, $view='', SchoolYearRepository $schoolYearRepo, SchoolYear $schoolYear)
    {
        if(empty(session()->get('schoolYearView')))  session()->put('schoolYearView', 'showInfo');
        if($view)  session()->put('schoolYearView', $view);
        $schoolYear = $schoolYearRepo -> find($id);
        $previous = $schoolYearRepo -> PreviousRecordId($id);
        $next = $schoolYearRepo -> NextRecordId($id);

        switch(session()->get('schoolYearView')) {
          case 'showInfo':
              return view('schoolYear.showInfo', ["schoolYear"=>$schoolYear, "previous"=>$previous, "next"=>$next]);
              exit;
          break;
          case 'showClasses':
              $grades = Grade::where('year_of_beginning', '<', $schoolYear->date_end)
                            -> where('year_of_graduation', '>', $schoolYear->date_start) -> get();
              return view('schoolYear.showClasses', ["schoolYear"=>$schoolYear, "grades"=>$grades, "previous"=>$previous, "next"=>$next]);
              exit;
          break;
          case 'showStudents':
              //$students = Student::where('first_year_id', '>', $id);
              return view('schoolYear.showStudents', ["schoolYear"=>$schoolYear, "previous"=>$previous, "next"=>$next]);
              exit;
          break;
          case 'showTeachers':
              $teachers = Teacher::where('first_year_id', '>', $id);
              return view('schoolYear.showTeachers', ["schoolYear"=>$schoolYear, "previous"=>$previous, "next"=>$next]);
              exit;
          break;
          case 'showGroups':
              $groups = Group::where('first_year_id', '>', $id);
              return view('schoolYear.showGroups', ["schoolYear"=>$schoolYear, "previous"=>$previous, "next"=>$next]);
              exit;
          break;
          case 'showTextbooks':
              return view('schoolYear.showTextbooks', ["schoolYear"=>$schoolYear, "previous"=>$previous, "next"=>$next]);
              exit;
          break;
          default:
              echo 'Widok nieznany';
              exit;
          break;
        }
    }



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
