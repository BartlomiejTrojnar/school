<?php
namespace App\Http\Controllers;
use App\Models\SchoolYear;
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

    public function show($id, $view, SchoolYearRepository $schoolYearRepo)
    {
        $schoolYear = $schoolYearRepo -> find($id);
        $previous = $schoolYearRepo -> PreviousRecordId($id);
        $next = $schoolYearRepo -> NextRecordId($id);

        switch($view) {
             case 'showInfo':
               return view('schoolYear.showInfo', ["schoolYear"=>$schoolYear, "previous"=>$previous, "next"=>$next]);
               exit;
             break;
             case 'showClasses':
               return view('schoolYear.showClasses', ["schoolYear"=>$schoolYear, "previous"=>$previous, "next"=>$next]);
               //$taughtSubjects = TaughtSubject::where('teacher_id', $id)->get();
               //$nonTaughtSubjects = TaughtSubject::nonTaughtSubjects($taughtSubjects);
               //return view('teacher.showSubjects', ["teacher"=>$teacher, "previous"=>$previous, "next"=>$next,
               //    "taughtSubjects"=>$taughtSubjects, "nonTaughtSubjects"=>$nonTaughtSubjects]);
               exit;
             break;
             case 'showStudents':
               return view('schoolYear.showStudents', ["schoolYear"=>$schoolYear, "previous"=>$previous, "next"=>$next]);
               exit;
             break;
             case 'showTeachers':
               return view('schoolYear.showTeachers', ["schoolYear"=>$schoolYear, "previous"=>$previous, "next"=>$next]);
               exit;
             break;
             case 'showGroups':
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
