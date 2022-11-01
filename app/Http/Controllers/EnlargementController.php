<?php
// ------------------------ (C) mgr inż. Bartłomiej Trojnar; 01.11.2022 ------------------------ //
namespace App\Http\Controllers;
use App\Models\Enlargement;

use App\Repositories\StudentGradeRepository;
use App\Repositories\SubjectRepository;
use Illuminate\Http\Request;

class EnlargementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    public function edit(Request $request, Enlargement $enlargement, StudentGradeRepository $studentGradeRepo, SubjectRepository $subjectRepo) {
        $enlargement = $enlargement -> find($request->id);
        $grades[0] = session()->get('gradeSelected');
        $studentGrades = $studentGradeRepo -> getStudentsFromGrades($grades, 0);
        $students = [];
        foreach($studentGrades as $studentGrade) $students[] = $studentGrade->student;
        $studentSF = view('student.selectField', ["students"=>$students, "studentSelected"=>$enlargement->student_id]);
        $subjects = $subjectRepo -> getActualAndSorted();
        $subjectSF = view('subject.selectField', ["subjects"=>$subjects, "subjectSelected"=>$enlargement->subject_id]);
        return view('enlargement.edit', ["enlargement"=>$enlargement, "version"=>$request->version, "studentSF"=>$studentSF, "subjectSF"=>$subjectSF]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
