<?php
// ------------------------ (C) mgr inż. Bartłomiej Trojnar; 06.11.2022 ------------------------ //
namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\CertificateTemplate;

use Illuminate\Http\Request;


class CertificateController extends Controller
{
    public function create(Request $request) {
        $templates = CertificateTemplate::all();
        $templateSF = view('certificate.templateSelectField', ["templates"=>$templates, "tempSelected"=>1]);
        $types = array("arkusz", "świadectwo");
        $typeSF = view('certificate.typeSelectField', ["types"=>$types, "typeSelected"=>"świadectwo"]);
        $student = session()->get('studentSelected');
        return view('certificate.create', ["version"=>$request->version, "student"=>$student, "templateSF"=>$templateSF, "typeSF"=>$typeSF]);
    }

    public function store(Request $request) {
        $this->validate($request, [ 'student_id' => 'required', ]);

        $certificate = new Certificate;
        $certificate->student_id    = $request->student_id;
        $certificate->type          = $request->type;
        $certificate->templates_id  = $request->templates_id;
        $certificate->council_date  = $request->council_date;
        $certificate->date_of_issue = $request->date_of_issue;
        $certificate -> save();
        return $certificate->id;
    }

    public function edit(Request $request, Certificate $certificate) {
        $certificate = $certificate -> find($request->id);
        $types = array("arkusz", "świadectwo");
        $typeSF = view('certificate.typeSelectField', ["types"=>$types, "typeSelected"=>$certificate->type]);
        $templates = CertificateTemplate::all();
        $templateSF = view('certificate.templateSelectField', ["templates"=>$templates, "tempSelected"=>$certificate->templates_id]);
        return view('certificate.edit', ["certificate"=>$certificate, "version"=>$request->version, "typeSF"=>$typeSF, "templateSF"=>$templateSF]);
    }

    public function update($id, Request $request, Certificate $certificate) {
        $certificate = $certificate -> find($id);
        $this->validate($request, [ 'student_id' => 'required', ]);

        $certificate->student_id    = $request->student_id;
        $certificate->type          = $request->type;
        $certificate->templates_id  = $request->templates_id;
        $certificate->council_date  = $request->council_date;
        $certificate->date_of_issue = $request->date_of_issue;
        $certificate -> save();

        return $certificate->id;
    }

    public function destroy($id, Certificate $certificate) {
        $certificate = $certificate -> find($id);
        $certificate -> delete();
        return 1;
    }

    public function refreshRow(Request $request, Certificate $certificate) {
        $certificate = $certificate -> find($request->id);
        return view('certificate.row', ["certificate"=>$certificate, "version"=>$request->version, "lp"=>$request->lp]);
    }
}
