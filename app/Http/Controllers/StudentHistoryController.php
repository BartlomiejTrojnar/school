<?php
// ------------------------ (C) mgr inż. Bartłomiej Trojnar; 06.05.2021 ------------------------ //
namespace App\Http\Controllers;
use App\Models\StudentHistory;

use App\Repositories\StudentRepository;
use App\Models\StudentGrade;
use Illuminate\Http\Request;

class StudentHistoryController extends Controller
{
    public function create(Request $request, StudentRepository $studentRepo) {
        if($request->version == "forStudent")     return $this -> createForStudent($request->student_id, $studentRepo);
        return $request->version;
    }

    private function createForStudent($student_id, $studentRepo) {
        $student = $studentRepo -> find($student_id);
        //return 20;
        $sh = StudentHistory :: orderby('id', 'desc')->first();
        return view('StudentHistory.createForStudent', ["student"=>$student, "proposedDateHistory"=>$sh->date]);
    }

    public function store(Request $request, StudentGrade $studentGrade) {
        $this->validate($request, [
          'student_id' => 'required',
          'date' => 'required',
          'event' => 'required',
        ]);

        $StudentHistory = new StudentHistory;
        $StudentHistory->student_id = $request->student_id;
        $StudentHistory->date = $request->date;
        $StudentHistory->event = $request->event;
        $StudentHistory->confirmation_date = $request->confirmation_date;
        $StudentHistory->confirmation_event = $request->confirmation_event;
        $StudentHistory -> save();

        return $StudentHistory->id;
    }

    public function edit(Request $request, StudentHistory $studentHistory) {
        $studentHistory = $studentHistory -> find($request->id);
        if( $request->version=="forStudent" )   return view('studentHistory.editForStudent', ["studentHistory"=>$studentHistory]);
        return $request->version;
    }

    public function update(Request $request, StudentHistory $studentHistory) {
        $this->validate($request, [
          'student_id' => 'required',
          'date' => 'required',
          'event' => 'required',
        ]);

        $studentHistory = StudentHistory::find($request->id);
        $studentHistory->student_id = $request->student_id;
        $studentHistory->date = $request->date;
        $studentHistory->event = $request->event;
        $studentHistory->confirmation_date = $request->confirmation_date;
        $studentHistory->confirmation_event = $request->confirmation_event;
        $studentHistory -> save();
        return $studentHistory->id;
    }

    public function destroy($id, StudentHistory $studentHistory) {
        $studentHistory = $studentHistory -> find($id);
        $studentHistory -> delete();
    }

    public function refreshRow(Request $request, StudentHistory $studentHistory) {
        $studentHistory = $studentHistory -> find($request->id);
        if( $request->version=="forStudent" )   return view('studentHistory.rowForStudent', ["studentHistory"=>$studentHistory]);
        return $request->version;
    }
}