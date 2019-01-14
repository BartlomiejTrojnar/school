<?php
namespace App\Http\Controllers;
use App\Models\School;
use App\Repositories\SchoolRepository;
use Illuminate\Http\Request;

class SchoolController extends Controller
{

    public function index(SchoolRepository $schoolRepo)
    {
        $schools = $schoolRepo->getAllSorted();
        return view('school.index', ["schools"=>$schools]);
    }

    public function orderBy($column)
    {
        if(session()->get('SchoolOrderBy[0]') == $column)
          if(session()->get('SchoolOrderBy[1]') == 'desc')
            session()->put('SchoolOrderBy[1]', 'asc');
          else
            session()->put('SchoolOrderBy[1]', 'desc');
        else
        {
          session()->put('SchoolOrderBy[2]', session()->get('SchoolOrderBy[0]'));
          session()->put('SchoolOrderBy[0]', $column);
          session()->put('SchoolOrderBy[3]', session()->get('SchoolOrderBy[1]'));
          session()->put('SchoolOrderBy[1]', 'asc');
        }

        return redirect( route('szkola.index') );
    }

    public function create()
    {
        return view('school.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
          'name' => 'required|max:40',
          'id_OKE' => 'max:12',
        ]);

        $school = new School;
        $school->name = $request->name;
        $school->id_OKE = $request->id_OKE;
        $school->save();

        return redirect($request->history_view);
    }

    public function show($id, $view='', SchoolRepository $schoolRepo)
    {
        session()->put('schoolSelected', $id);
        if(empty(session()->get('schoolView')))  session()->put('schoolView', 'showInfo');
        if($view)  session()->put('schoolView', $view);
        $school = $schoolRepo -> find($id);
        $previous = $schoolRepo->PreviousRecordId($id);
        $next = $schoolRepo->NextRecordId($id);

        switch(session()->get('schoolView')) {
          case 'showInfo':
              return view('school.show', ["school"=>$school, "previous"=>$previous, "next"=>$next])
                  -> nest('subView', 'school.showInfo', ["school"=>$school]);
          break;
          case 'showStudents':
              $bookOfStudents = $school -> students;
              foreach($bookOfStudents as $book) $students[] = $book->student;
              if(empty($students)) $students='';
              return view('school.show', ["school"=>$school, "previous"=>$previous, "next"=>$next])
                  -> nest('subView', 'student.table', ["school"=>$school, "students"=>$students, "subTitle"=>"uczniowie szkoły"]);
          break;
          case 'showClasses':
              $subTitle = "Klasy w szkole";
              $schoolSelectField = 0;

              $grades = $school -> grades()
                  -> orderBy( session()->get('GradeOrderBy[0]'), session()->get('GradeOrderBy[1]') )
                  -> orderBy( session()->get('GradeOrderBy[2]'), session()->get('GradeOrderBy[3]') )
                  -> paginate(20);
              return view('school.show', ["school"=>$school, "previous"=>$previous, "next"=>$next])
                  -> nest('subView', 'grade.table', ["school"=>$school, "subTitle"=>$subTitle, "grades"=>$grades, "links"=>true, "schoolSelectField"=>$schoolSelectField]);
          break;
          case 'change':
              return redirect( $_SERVER['HTTP_REFERER'] );
          break;
          default:
              printf('<p style="background: #bb0; color: #f00; font-size: x-large; text-align: center; border: 3px solid red; padding: 5px;">Widok %s nieznany</p>', session()->get('schoolView'));
              exit;
          break;
        }
    }

    public function edit(School $szkola)
    {
        return view('school.edit', ["school"=>$szkola]);
    }

    public function update(Request $request, School $szkola)
    {
        $this->validate($request, [
          'name' => 'required|max:40',
          'id_OKE' => 'max:12',
        ]);

        $szkola->name = $request->name;
        $szkola->id_OKE = $request->id_OKE;
        $szkola->save();

        return redirect($request->history_view);
    }

    public function destroy(School $szkola)
    {
        $szkola->delete();
        return redirect( $_SERVER['HTTP_REFERER'] );
    }
}