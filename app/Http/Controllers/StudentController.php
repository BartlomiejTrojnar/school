<?php
namespace App\Http\Controllers;
use App\Models\Student;
use App\Models\StudentClass;
use App\Repositories\StudentRepository;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index(StudentRepository $studentRepo)
    {
        for($i=0; $i<6; $i++)
          $orderBy[$i] = session()->get("StudentOrderBy[$i]");

        $students = $studentRepo->getAll($orderBy);
        return view('student.index', ["students"=>$students]);
    }

    public function orderBy($column)
    {
        if(session()->get('StudentOrderBy[0]') == $column)
          if(session()->get('StudentOrderBy[1]') == 'desc')
            session()->put('StudentOrderBy[1]', 'asc');
          else
            session()->put('StudentOrderBy[1]', 'desc');
        else
        {
          session()->put('StudentOrderBy[4]', session()->get('StudentOrderBy[2]'));
          session()->put('StudentOrderBy[2]', session()->get('StudentOrderBy[0]'));
          session()->put('StudentOrderBy[0]', $column);
          session()->put('StudentOrderBy[5]', session()->get('StudentOrderBy[3]'));
          session()->put('StudentOrderBy[3]', session()->get('StudentOrderBy[1]'));
          session()->put('StudentOrderBy[1]', 'asc');
        }
        return redirect( route('uczen.index') );
    }

    public function create()
    {
        $selectedSex = 'kobieta';
        return view('student.create')
            ->nest('sexSelectField', 'student.sexSelectField', ["sex"=>$selectedSex]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
          'first_name' => 'required|max:20',
          'second_name' => 'max:12',
          'last_name' => 'required|max:18',
          'second_name' => 'max:15',
          'sex' => 'required',
          'pesel' => 'min:11|max:11|unique',
          'place_of_birth' => 'max:20',
        ]);

        $student = new Student;
        $student->first_name = $request->first_name;
        $student->second_name = $request->second_name;
        $student->last_name = $request->last_name;
        $student->family_name = $request->family_name;
        $student->sex = $request->sex;
        $student->PESEL = $request->PESEL;
        $student->place_of_birth = $request->place_of_birth;
        $student->save();

        return redirect($request->history_view);
    }

    public function show(Student $uczen, StudentRepository $studentRepo)
    {
        $studentClasses = StudentClass::all()->where('student_id', $uczen->id);
        $previous = $studentRepo->previousRecordId($uczen->id);
        $next = $studentRepo->nextRecordId($uczen->id);
        return view('student.show', ["student"=>$uczen, "studentClasses"=>$studentClasses, "previous"=>$previous, "next"=>$next]);
    }

    public function edit(Student $uczen)
    {
        return view('student.edit', ["student"=>$uczen])
            ->nest('sexSelectField', 'student.sexSelectField', ["sex"=>$uczen->sex]);
    }

    public function update(Request $request, Student $uczen)
    {
        $this->validate($request, [
          'first_name' => 'required|max:20',
          'second_name' => 'max:12',
          'last_name' => 'required|max:18',
          'second_name' => 'max:15',
          'sex' => 'required',
          'pesel' => 'min:11|max:11|unique',
          'place_of_birth' => 'max:20',
        ]);

        $uczen->first_name = $request->first_name;
        $uczen->second_name = $request->second_name;
        $uczen->last_name = $request->last_name;
        $uczen->family_name = $request->family_name;
        $uczen->sex = $request->sex;
        $uczen->PESEL = $request->PESEL;
        $uczen->place_of_birth = $request->place_of_birth;
        $uczen->save();

        return redirect($request->history_view);
    }

    public function destroy(Student $uczen)
    {
        $uczen->delete();
        return redirect( $_SERVER['HTTP_REFERER'] );
    }
}
