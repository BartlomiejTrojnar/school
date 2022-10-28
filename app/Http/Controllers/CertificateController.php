<?php
// ------------------------ (C) mgr inż. Bartłomiej Trojnar; 26.10.2022 ------------------------ //
namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\CertificateTemplate;

use Illuminate\Http\Request;


class CertificateController extends Controller
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

    public function create(Request $request) {
        $templates = CertificateTemplate::all();
        $templateSF = view('certificate.templateSelectField', ["templates"=>$templates]);
        $types = array("arkusz", "świadectwo");
        $typeSF = view('certificate.typeSelectField', ["types"=>$types]);
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

    public function edit(Request $request, Certificate $certificate) {
        $certificate = $certificate -> find($request->id);
        $types = array("arkusz", "świadectwo");
        $typeSF = view('certificate.typeSelectField', ["types"=>$types, "typeSelected"=>$certificate->type]);
        $templates = CertificateTemplate::all();
        $templateSF = view('certificate.templateSelectField', ["templates"=>$templates, "tempSelected"=>$certificate->templates_id]);
        return view('certificate.edit', ["certificate"=>$certificate, "version"=>$request->version, "typeSF"=>$typeSF, "templateSF"=>$templateSF]);
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

    public function refreshRow(Request $request, Certificate $certificate) {
        $certificate = $certificate -> find($request->id);
        return view('certificate.row', ["certificate"=>$certificate, "version"=>$request->version, "lp"=>$request->lp]);
    }
}
