<?php
namespace App\Http\Controllers;
use App\Models\Certificate;
use App\Repositories\CertificateRepository;
use App\Repositories\StudentRepository;
use App\Repositories\CertificatePatternRepository;
use Illuminate\Http\Request;

class CertificateController extends Controller
{
    public function index(CertificateRepository $certificateRepo)
    {
        for($i=0; $i<6; $i++)
          $orderBy[$i] = session()->get("CertificateOrderBy[$i]");

        $certificates = $certificateRepo->getAll($orderBy);
        return view('certificate.index', ["certificates"=>$certificates]);
    }

    public function orderBy($column)
    {
        if(session()->get('CertificateOrderBy[0]') == $column)
          if(session()->get('CertificateOrderBy[1]') == 'desc')
            session()->put('CertificateOrderBy[1]', 'asc');
          else
            session()->put('CertificateOrderBy[1]', 'desc');
        else
        {
          session()->put('CertificateOrderBy[4]', session()->get('CertificateOrderBy[2]'));
          session()->put('CertificateOrderBy[2]', session()->get('CertificateOrderBy[0]'));
          session()->put('CertificateOrderBy[0]', $column);
          session()->put('CertificateOrderBy[5]', session()->get('CertificateOrderBy[3]'));
          session()->put('CertificateOrderBy[3]', session()->get('CertificateOrderBy[1]'));
          session()->put('CertificateOrderBy[1]', 'asc');
        }
        return redirect( route('swiadectwo.index') );
    }

    public function create(StudentRepository $studentRepo, CertificatePatternRepository $certificatePatternRepo)
    {
        $students = $studentRepo->getAll();
        $certificatePatterns = $certificatePatternRepo->getAll();
        return view('certificate.create')
             ->nest('studentSelectField', 'student.selectField', ["students"=>$students, "selectedStudent"=>0])
             ->nest('sheetPatternSelectField', 'certificatePattern.selectField', ["certificatePatterns"=>$certificatePatterns, "selectedCertificatePattern"=>0, "name"=>'sheet_pattern_id'])
             ->nest('certificatePatternSelectField', 'certificatePattern.selectField', ["certificatePatterns"=>$certificatePatterns, "selectedCertificatePattern"=>0, "name"=>'certificate_pattern_id']);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
          'student_id' => 'required',
          'sheet_pattern_id' => 'required',
          'certificate_pattern_id' => 'required',
          'date_of_council' => 'required|date',
          'date_of_release' => 'required|date',
        ]);

        $certificate = new Certificate;
        $certificate->student_id = $request->student_id;
        $certificate->sheet_pattern_id = $request->sheet_pattern_id;
        $certificate->certificate_pattern_id = $request->certificate_pattern_id;
        $certificate->date_of_council = $request->date_of_council;
        $certificate->date_of_release = $request->date_of_release;
        $certificate->save();

        return redirect($request->history_view);
    }

    public function show(Certificate $swiadectwo, CertificateRepository $certificateRepo)
    {
        $previous = $certificateRepo->previousRecordId($swiadectwo->id);
        $next = $certificateRepo->nextRecordId($swiadectwo->id);
        return view('certificate.show', ["certificate"=>$swiadectwo, "previous"=>$previous, "next"=>$next]);
    }

    public function edit(Certificate $swiadectwo, StudentRepository $studentRepo, CertificatePatternRepository $certificatePatternRepo)
    {
        $students = $studentRepo->getAll();
        $certificatePatterns = $certificatePatternRepo->getAll();
        return view('certificate.edit', ["certificate"=>$swiadectwo])
             ->nest('studentSelectField', 'student.selectField', ["students"=>$students, "selectedStudent"=>$swiadectwo->student_id])
             ->nest('sheetPatternSelectField', 'certificatePattern.selectField', ["certificatePatterns"=>$certificatePatterns, "selectedCertificatePattern"=>$swiadectwo->sheet_pattern_id, "name"=>'sheet_pattern_id'])
             ->nest('certificatePatternSelectField', 'certificatePattern.selectField', ["certificatePatterns"=>$certificatePatterns, "selectedCertificatePattern"=>$swiadectwo->certificate_pattern_id, "name"=>'certificate_pattern_id']);
    }

    public function update(Request $request, Certificate $swiadectwo)
    {
        $this->validate($request, [
          'student_id' => 'required',
          'sheet_pattern_id' => 'required',
          'certificate_pattern_id' => 'required',
          'date_of_council' => 'required|date',
          'date_of_release' => 'required|date',
        ]);

        $swiadectwo->student_id = $request->student_id;
        $swiadectwo->sheet_pattern_id = $request->sheet_pattern_id;
        $swiadectwo->certificate_pattern_id = $request->certificate_pattern_id;
        $swiadectwo->date_of_council = $request->date_of_council;
        $swiadectwo->date_of_release = $request->date_of_release;
        $swiadectwo->save();

        return redirect($request->history_view);
    }

    public function destroy(Certificate $swiadectwo)
    {
        $swiadectwo->delete();
        return redirect( $_SERVER['HTTP_REFERER'] );
    }
}
