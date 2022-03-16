<?php
namespace App\Http\Controllers;

use App\Models\TaughtSubject;
use Illuminate\Http\Request;

class TaughtSubjectController extends Controller
{
    public function sprawdzNauczanie() {
        return view('teacher.sprawdzNauczanie');
    }

    public function store(Request $request) {
        $this->validate($request, [
            'teacher_id' => 'required',
            'subject_id' => 'required',
        ]);

        $taughtSubject = new TaughtSubject;
        $taughtSubject->teacher_id = $request->teacher_id;
        $taughtSubject->subject_id = $request->subject_id;
        $taughtSubject->save();
        $record = TaughtSubject::orderBy('id', 'desc')->first();
        if(!empty($request->history_view))  return redirect($request->history_view);
        return $record->id;
    }

    public function destroy($id) {  TaughtSubject::destroy($id);  }
}
