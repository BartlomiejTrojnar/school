<?php
// ------------------------ (C) mgr inż. Bartłomiej Trojnar; 10.01.2023 ------------------------ //
namespace App\Http\Controllers;
use App\Models\Classroom;
use App\Repositories\ClassroomRepository;

use App\Repositories\ExamRepository;
use App\Repositories\LessonPlanRepository;
use App\Repositories\TermRepository;
use Illuminate\Http\Request;

class ClassroomController extends Controller
{
    public function create() {  return view('classroom.create');  }

    public function store(Request $request) {
        $this->validate($request, [
          'name' => 'required|max:20',
          'capacity' => 'required|integer|max:100',
          'floor' => 'required|integer|min:0|max:2',
          'line' => 'required|integer|min:1|max:10',
          'column' => 'required|integer|min:1|max:10',
        ]);

        $classroom = new Classroom;
        $classroom->name = $request->name;
        $classroom->capacity = $request->capacity;
        $classroom->floor = $request->floor;
        $classroom->line = $request->line;
        $classroom->column = $request->column;
        $classroom -> save();

        return $classroom->id;
    }

    public function edit($id, Request $request, Classroom $classroom) {
        $classroom = $classroom -> find($id);
        return view('classroom.edit', ["classroom"=>$classroom, "lp"=>$request->lp]);
    }

    public function update($id, Request $request, Classroom $classroom) {
        $classroom = $classroom -> find($id);
        $this->validate($request, [
            'name' => 'required|max:20',
            'capacity' => 'required|integer|max:100',
            'floor' => 'required|integer|min:0|max:2',
            'line' => 'required|integer|min:1|max:10',
            'column' => 'required|integer|min:1|max:10',
        ]);

        $classroom->name = $request->name;
        $classroom->capacity = $request->capacity;
        $classroom->floor = $request->floor;
        $classroom->line = $request->line;
        $classroom->column = $request->column;
        $classroom -> save();

        return $classroom->id;
    }

    public function destroy($id, Classroom $classroom) {
        $classroom = $classroom -> find($id);
        $classroom -> delete();
        return 1;
    }

    public function refreshRow(Request $request, ClassroomRepository $classroomRepo) {
        $this->classroom = $classroomRepo -> find($request->id);
        return view('classroom.row', ["classroom"=>$this->classroom, "lp"=>$request->lp]);
    }

    public function index(ClassroomRepository $classroomRepo) {
        $classrooms = $classroomRepo -> getAllSorted();
        return view('classroom.index', ["classrooms"=>$classrooms]);
    }

    public function show($id, ClassroomRepository $classroomRepo, LessonPlanRepository $lessonPlanRepo, TermRepository $termRepo, ExamRepository $examRepo, $view='') {
        $this->classroom = $classroomRepo -> find($id);
        session()->put('classroomSelected', $id);
        if(empty(session()->get('classroomView')))  session()->put('classroomView', 'planlekcji');
        if($view)  session()->put('classroomView', $view);
        $classrooms = $classroomRepo->getAllSorted();
        list($this->previous, $this->next) = $classroomRepo -> nextAndPreviousRecordId($classrooms, $id);

        switch(session()->get('classroomView')) {
            case 'planlekcji':  return $this -> showLessonPlan($lessonPlanRepo);
            case 'terminy':     return $this -> showTerms($termRepo);
            case 'egzaminy':    return $this -> showExams($examRepo);
            default:
                printf('<p style="background: #bb0; color: #f00; font-size: x-large; text-align: center; border: 3px solid red; padding: 5px;">Widok %s nieznany</p>', session()->get('classroomView'));
            break;
        }
    }

    private function showLessonPlan($lessonPlanRepo) {
        $js = "lessonPlan/forClassroom.js";
        $css = "lessonPlan.css";
        $lessons = $lessonPlanRepo -> getClassroomLessons($this->classroom->id);
        $dateView = session()->get('dateView');
        $studyYear = substr($dateView,0,4) - $lessons[0]->group->grades[0]->grade->year_of_beginning;
        $classroomPlan = view('lessonPlan.classroomPlan', ["lessons"=>$lessons, "dateView"=>$dateView, "studyYear"=>$studyYear]);
        return view('classroom.show', ["classroom"=>$this->classroom, "previous"=>$this->previous, "next"=>$this->next, "css"=>$css, "js"=>$js, "subView"=>$classroomPlan]);
    }

    private function showTerms($termRepo) {
        $terms = $termRepo -> getFilteredAndSorted(0, 0, $this->classroom->id);
        $termTable = view('term.table', ["terms"=>$terms, "sessionSF"=>"", "examDescriptionSF"=>"", "classroomSF"=>""]);
        return view('classroom.show', ["classroom"=>$this->classroom, "previous"=>$this->previous, "next"=>$this->next, "subView"=>$termTable]);
    }

    private function showExams($examRepo) {
        $exams = $examRepo -> getFilteredAndSorted(0, 0, 0, $this->classroom->id, 0);
        $examTable = view('exam.table', ["exams"=>$exams, "declarationSF"=>"", "examDescriptionSF"=>"", "termSF"=>"", "examTypeSF"=>""]);
        return view('classroom.show', ["classroom"=>$this->classroom, "previous"=>$this->previous, "next"=>$this->next, "subView"=>$examTable]);
    }

    public function orderBy($column) {
        if(session()->get('ClassroomOrderBy[0]') == $column)
            if(session()->get('ClassroomOrderBy[1]') == 'desc')  session()->put('ClassroomOrderBy[1]', 'asc');
            else  session()->put('ClassroomOrderBy[1]', 'desc');
        else {
            session()->put('ClassroomOrderBy[4]', session()->get('ClassroomOrderBy[2]'));
            session()->put('ClassroomOrderBy[2]', session()->get('ClassroomOrderBy[0]'));
            session()->put('ClassroomOrderBy[0]', $column);
            session()->put('ClassroomOrderBy[5]', session()->get('ClassroomOrderBy[3]'));
            session()->put('ClassroomOrderBy[3]', session()->get('ClassroomOrderBy[1]'));
            session()->put('ClassroomOrderBy[1]', 'asc');
        }
        return redirect( route('sala.index') );
    }

//    public function change($id) {  session()->put('classroomSelected', $id);  }
}