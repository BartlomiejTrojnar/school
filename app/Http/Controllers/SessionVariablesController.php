<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;

class SessionVariablesController extends Controller
{
    public function rememberDates(Request $request) {
        if( empty($_POST['dateView']) && empty($request->dateView) ) { echo 'zamień mnie na dateView!'; exit; }
        if( !empty($request->dateEnd) ) { echo 'zamień dateEnd na end!'; exit; }
        if(!empty($_POST['dateView']))  session() -> put('dateView', $_POST['dateView']);
        if(!empty($request->dateView))  session() -> put('dateView', $request->dateView);
        session() -> put('dateEnd', $request->end);
        return session() -> get('dateView');
    }

    public function typeChange($type) { session() -> put('typeSelected', $type); }
    public function studyYearChange($year) { session() -> put('studyYearSelected', $year); }
    public function levelChange($level) { session() -> put('levelSelected', $level); }
}