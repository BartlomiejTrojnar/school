<?php
namespace App\Models;
use App\Models\Subject;
use Illuminate\Database\Eloquent\Model;

class TaughtSubject extends Model
{
    public $timestamps = false;

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function nonTaughtSubjects($taughtSubjects)
    {
        $ts = array();
        foreach($taughtSubjects as $taughtSubject)
            $ts[] = $taughtSubject->subject_id;

        $subjects = Subject::where('actual', true)->whereNotIn('id', $ts)->get();
        return $subjects;
    }
}
