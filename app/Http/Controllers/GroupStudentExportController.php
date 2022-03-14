<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Repositories\GroupRepository;
use App\Repositories\GroupStudentRepository;
use App\Repositories\SchoolYearRepository;


class GroupStudentExportController extends Controller  {
    public function group(Request $request, GroupStudentRepository $groupStudentRepo, GroupRepository $groupRepo, SchoolYearRepository $schoolYearRepo)  {
        $dateView = session() -> get('dateView');
        $group = $groupRepo -> find($request->group_id);
        $schoolYear = $schoolYearRepo -> getSchoolYearIdForDate($dateView);
        $groupStudents = $groupStudentRepo -> getGroupStudents($request->group_id, $dateView);

        Excel::create('lista_uczniow_w_grupie_'.$group->id, function($excel) use ($groupStudents, $schoolYear, $dateView) {
            // Set the title
            $excel->setTitle('lista uczniów grupy');
            // Chain the setters
            $excel->setCreator('Group Manager by Bartłomiej Trojnar')
                  ->setCompany('II Liceum Ogólnokształcące w Łańcucie');
            // Call them separately
            $excel->setDescription('Wygenerowano za pomocą aplikacji Group Manager');

            $excel->sheet('grupa', function($sheet) use ($groupStudents, $schoolYear, $dateView) {
                $this -> saveGroupComposition($sheet, $groupStudents, $schoolYear, $dateView);
            });
        })->export('xlsx');
    }

    private function saveGroupComposition($sheet, $groupStudents, $schoolYear, $dateView) {
        $sheet->loadView('groupStudent.exportGroup', ["groupStudents"=>$groupStudents, "schoolYear"=>$schoolYear, "dateView"=>$dateView]);

        // formatowanie komórek
        $sheet->cells('A2', function($cells) {  $cells -> setFontSize( 16 );  });
        $sheet->cells('A4:H4', function($cells) {   $cells -> setBorder( array( 'bottom'=>array('style'=>'medium'), ));  });
        $MAXROW = count($groupStudents)+4;
        for($row = 5; $row<$MAXROW; $row++) {
            $sheet->cells('A'.$row.':H'.$row, function($cells) {    $cells -> setBorder( array( 'bottom'=>array('style'=>'dashed'), )); });    
        }
        $sheet->cells('A'.$MAXROW.':H'.$MAXROW, function($cells) {  $cells -> setBorder( array( 'bottom'=>array('style'=>'thin'), ));   });    
    }
}
