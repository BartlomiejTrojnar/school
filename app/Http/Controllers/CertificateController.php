<?php
// ------------------------ (C) mgr inż. Bartłomiej Trojnar; 25.10.2022 ------------------------ //
namespace App\Http\Controllers;
use App\Models\Certificate;

use App\Repositories\StudentRepository;
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
        $student = session()->get('studentSelected');
        return view('certificate.create', ["version"=>$request->version, "student"=>$student]);
    }

    public function store(Request $request) {
        $this->validate($request, [ 'student_id' => 'required', ]);
        return "dokończ metodę store w CertificateController";

        // $declaration = new Declaration;
        // $declaration->student_id = $request->student_id;
        // $declaration->session_id = $request->session_id;
        // $declaration->application_number = $request->application_number;
        // $declaration->student_code = $request->student_code;
        // $declaration -> save();
        //return $certificate->id;
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
