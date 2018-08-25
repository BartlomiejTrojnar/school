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
        $schoolYears = $schoolYearRepo->getAll($orderBy);
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

    public function show(SchoolYear $rok_szkolny, SchoolYearRepository $schoolYearRepo)
    {
        $previous = $schoolYearRepo->PreviousRecordId($rok_szkolny->id);
        $next = $schoolYearRepo->NextRecordId($rok_szkolny->id);
        return view('schoolYear.show', ["schoolYear"=>$rok_szkolny, "previous"=>$previous, "next"=>$next]);
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
