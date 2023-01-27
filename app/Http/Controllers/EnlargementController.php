<?php
// ------------------------ (C) mgr inż. Bartłomiej Trojnar; 27.01.2023 ------------------------ //
namespace App\Http\Controllers;
use App\Models\Enlargement;

use App\Repositories\StudentGradeRepository;
use App\Repositories\SubjectRepository;
use Illuminate\Http\Request;

class EnlargementController extends Controller
{
    public function create(Request $request, StudentGradeRepository $studentGradeRepo, SubjectRepository $subjectRepo) {
        $subjects = $subjectRepo -> getActualAndSorted();
        $subjectSF = view('subject.selectField', ["subjects"=>$subjects, "subjectSelected"=>session()->get('subjectSelected')]);
        if($request->version == "forStudent")   return view('enlargement.createForStudent', ["subjectSF"=>$subjectSF]);

        $grades[0] = session()->get('gradeSelected');
        $studentGrades = $studentGradeRepo -> getStudentsFromGrades($grades, 0);
        $students = [];
        foreach($studentGrades as $studentGrade) $students[] = $studentGrade->student;
        $studentSF = view('student.selectField', ["students"=>$students, "studentSelected"=>session()->get('studentSelected')]);
        return view('enlargement.create', ["studentSF"=>$studentSF, "subjectSF"=>$subjectSF]);
    }

    public function store(Request $request) {
        $this->validate($request, [ 'student_id' => 'required', 'subject_id' => 'required', ]);
        $enlargement = new Enlargement;
        $enlargement->student_id = $request->student_id;
        $enlargement->subject_id = $request->subject_id;
        $enlargement->level = $request->level;
        $enlargement->choice = $request->choice;
        if($request->resignation=="")   $enlargement->resignation = NULL;
        else $enlargement->resignation = $request->resignation;
        $enlargement -> save();
        return $enlargement->id;
    }

    public function edit(Request $request, Enlargement $enlargement, StudentGradeRepository $studentGradeRepo, SubjectRepository $subjectRepo) {
        $enlargement = $enlargement -> find($request->id);
        $subjects = $subjectRepo -> getActualAndSorted();
        $subjectSF = view('subject.selectField', ["subjects"=>$subjects, "subjectSelected"=>$enlargement->subject_id]);
        if($request->version == "forStudent") return view('enlargement.editForStudent', ["enlargement"=>$enlargement, "subjectSF"=>$subjectSF]);

        $grades[0] = session()->get('gradeSelected');
        $studentGrades = $studentGradeRepo -> getStudentsFromGrades($grades, 0);
        $students = [];
        foreach($studentGrades as $studentGrade) $students[] = $studentGrade->student;
        $studentSF = view('student.selectField', ["students"=>$students, "studentSelected"=>$enlargement->student_id]);
        return view('enlargement.edit', ["enlargement"=>$enlargement, "studentSF"=>$studentSF, "subjectSF"=>$subjectSF, "lp"=>$request->lp]);
    }

    public function update(Request $request, Enlargement $enlargement) {
        $enlargement = $enlargement -> find($request->id);
        $this->validate($request, [ 'student_id' => 'required', 'subject_id' => 'required', ]);
        $enlargement->student_id = $request->student_id;
        $enlargement->subject_id = $request->subject_id;
        $enlargement->level = $request->level;
        $enlargement->choice = $request->choice;
        if($request->resignation=="")   $enlargement->resignation = NULL;
        else $enlargement->resignation = $request->resignation;
        $enlargement -> save();
        return $enlargement->id;
    }

    public function destroy($id, Enlargement $enlargement)  {
        $enlargement = $enlargement -> find($id);
        $enlargement -> delete();
        return 1;
    }

    public function refreshRow(Request $request, Enlargement $enlargement) {
        $enlargement = $enlargement -> find($request->id);
        if($request->version == "forStudent") return view('enlargement.choiceForStudent', ["enlargement"=>$enlargement, "version"=>$request->version, "lp"=>$request->lp]);
        return view('enlargement.row', ["enlargement"=>$enlargement, "version"=>$request->version, "lp"=>$request->lp]);
    }
}
