<?php
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return Redirect::action('Auth\AuthController@getLogin');
});

// Uwierzytelnianie
Route::get('auth/login', array('as'=>'login', 'uses'=>'Auth\AuthController@getLogin'));
Route::post('auth/login', 'Auth\AuthController@postLogin');
Route::any('auth/logout',  array('as'=>'logout', 'uses'=>'Auth\AuthController@getLogout'));

// Rejestracja
Route::get('auth/register',  array('as'=>'register', 'uses'=>'Auth\AuthController@getRegister'));
Route::post('auth/register', 'Auth\AuthController@postRegister');

Route::get('/home', ['middleware' => 'auth', function () {
    session()->put('dateView', date('Y-m-d'));
    return view('home');
}]);

/* --------------------------------------------------------------------------- */
Route::post('/rememberDates', 'SessionVariablesController@rememberDates');
Route::post('/level/change/{level}', 'SessionVariablesController@levelChange');
Route::post('/studyYear/change/{year}', 'SessionVariablesController@studyYearChange');
Route::post('/type/change/{type}', 'SessionVariablesController@typeChange');


/* --------------------------------------------------------------------------- */

Route::resource('/szkola', 'SchoolController');
Route::get('/szkola/orderBy/{column}', array('as'=>'szkola.orderBy', 'uses'=>'SchoolController@orderBy'));
Route::get('/szkola/{id}/{view?}', 'SchoolController@show');
Route::post('/szkola/change/{id}', 'SchoolController@change');
Route::post('/szkola/refreshRow', 'SchoolController@refreshRow');

Route::resource('/rok_szkolny', 'SchoolYearController');
Route::post('/rok_szkolny/change/{id}', 'SchoolYearController@change');
Route::get('/rok_szkolny/{id}/{view?}', 'SchoolYearController@show');
Route::post('/rok_szkolny/refreshRow', 'SchoolYearController@refreshRow');

Route::get('/uczen/import', array('as'=>'uczen.import', 'uses'=>'StudentImportController@import'));
Route::get('/uczen/search', array('as'=>'uczen.search', 'uses'=>'StudentController@search'));
Route::post('/uczen/search_results', array('as'=>'uczen.search_results', 'uses'=>'StudentController@searchResults'));
Route::resource('/uczen', 'StudentController');
Route::get('/uczen/orderBy/{column}', array('as'=>'uczen.orderBy', 'uses'=>'StudentController@orderBy'));
Route::post('/uczen/change/{id}', 'StudentController@change');
Route::get('/uczen/{id}/{view?}', 'StudentController@show');
Route::post('/uczen/refreshRow', 'StudentController@refreshRow');

Route::resource('/ksiega_uczniow', 'BookOfStudentController');
Route::get('/ksiega_uczniow/orderBy/{column}', array('as'=>'ksiega_uczniow.orderBy', 'uses'=>'BookOfStudentController@orderBy'));
Route::post('/ksiega_uczniow/refreshRow', 'BookOfStudentController@refreshRow');

Route::post('/klasa/refreshRow', 'GradeController@refreshRow');
Route::get('/klasa/orderBy/{column}', array('as'=>'klasa.orderBy', 'uses'=>'GradeController@orderBy'));
Route::post('/klasa/change/{id}', 'GradeController@change');
Route::get('/klasa/getDates/{id}', 'GradeController@getDates');
Route::resource('/klasa', 'GradeController');
Route::get('/klasa/{id}/{view?}', 'GradeController@show');

Route::post('/klasy_ucznia/editAll', array('as'=>'klasy_ucznia.editAll', 'uses'=>'StudentGradeController@editAll'));
Route::get('/klasy_ucznia/addMany', array('as'=>'klasy_ucznia.addMany', 'uses'=>'StudentGradeController@addMany'));
Route::post('/klasy_ucznia/refreshTable', 'StudentGradeController@refreshTable');
Route::post('/klasy_ucznia/refreshRow', 'StudentGradeController@refreshRow');
Route::resource('/klasy_ucznia', 'StudentGradeController');
Route::get('/klasy_ucznia/orderBy/{column}', array('as'=>'klasy_ucznia.orderBy', 'uses'=>'StudentGradeController@orderBy'));
Route::delete('/klasy_ucznia/{id}', 'StudentGradeController@destroy');
Route::post('/klasy_ucznia/updateNumber', 'StudentGradeController@updateNumber');
Route::post('/klasy_ucznia/updateAll', array('as'=>'klasy_ucznia.updateAll', 'uses'=>'StudentGradeController@updateAll'));
Route::post('/klasy_ucznia/createFromPreviousYear', 'StudentGradeController@createFromPreviousYear');
Route::post('/klasy_ucznia/storeFromPreviousYear', array('as'=>'klasy_ucznia.storeFromPreviousYear', 'uses'=>'StudentGradeController@storeFromPreviousYear'));
Route::put('/studentGrade/updateEnd', 'StudentGradeController@updateEnd');

Route::resource('/numery_ucznia', 'StudentNumberController');
Route::get('/numery_ucznia/orderBy/{column}', array('as'=>'numery_ucznia.orderBy', 'uses'=>'StudentNumberController@orderBy'));
Route::post('/student_numbers/confirmNumbers/{grade_id}/{school_year_id}', 'StudentNumberController@confirmNumbers');
Route::post('/student_numbers/copy', array('as'=>'numery_ucznia.copy', 'uses'=>'StudentNumberController@copy'));
Route::post('/student_numbers/refreshSection', 'StudentNumberController@refreshSection');
Route::post('/numery_ucznia/refreshRow', 'StudentNumberController@refreshRow');
Route::post('/student_numbers/updateNumber', 'StudentNumberController@updateNumber');
Route::post('/student_numbers/addNumbersForGrade', 'StudentNumberController@addNumbersForGrade');

Route::resource('/historia_ucznia', 'StudentHistoryController');
Route::post('/historia_ucznia/refreshRow', 'StudentHistoryController@refreshRow');


// -------------------------------------------------------------------------------------------------------- //
Route::resource('/sala', 'ClassroomController');
Route::get('/sala/orderBy/{column}', array('as'=>'sala.orderBy', 'uses'=>'ClassroomController@orderBy'));
Route::get('/sala/{id}/{view?}', 'ClassroomController@show');
Route::post('/sala/change/{id}', 'ClassroomController@change');
Route::post('/sala/refreshRow', 'ClassroomController@refreshRow');
Route::post('/classroomPlan/showStudentListForGroup', 'LessonPlanController@showStudentListForGroup');

Route::resource('/przedmiot', 'SubjectController');
Route::post('/przedmiot/change/{id}', 'SubjectController@change');
Route::get('/przedmiot/orderBy/{column}', array('as'=>'przedmiot.orderBy', 'uses'=>'SubjectController@orderBy'));
Route::get('/przedmiot/{id}/{view?}', 'SubjectController@show');
Route::post('/przedmiot/refreshRow', 'SubjectController@refreshRow');

Route::get('/nauczyciel/printOrder', array('as'=>'nauczyciel.printOrder', 'uses'=>'TeacherController@printOrder'));
Route::resource('/nauczyciel', 'TeacherController');
Route::get('/nauczyciel/orderBy/{column}', array('as'=>'nauczyciel.orderBy', 'uses'=>'TeacherController@orderBy'));
Route::get('/nauczyciel/{id}/{view?}', 'TeacherController@show');
Route::post('/nauczyciel/change/{id}', 'TeacherController@change');
Route::post('/nauczyciel/setPrintOrder', 'TeacherController@setPrintOrder');
Route::post('/nauczyciel/refreshRow', 'TeacherController@refreshRow');

Route::resource('/nauczany_przedmiot', 'TaughtSubjectController');
Route::delete('/nauczany_przedmiot/delete/{id}', 'TaughtSubjectController@destroy');
// -------------------------------------------------------------------------------------------------------- //

Route::post('/rozszerzenie/exchangeStore', array('as'=>'rozszerzenie.exchangeStore', 'uses'=>'EnlargementController@exchangeStore'));
Route::resource('/rozszerzenie', 'EnlargementController');
Route::get('/rozszerzenie/exchange/{id}', 'EnlargementController@exchange');
Route::put('/rozszerzenie/exchangeUpdate/{id}', 'EnlargementController@exchangeUpdate');
Route::post('/rozszerzenie/refreshRow', 'EnlargementController@refreshRow');

Route::get('/grupa/editComments', array('as'=>'grupa.editComments', 'uses'=>'GroupController@editComments'));
Route::post('/grupa/updateComments', array('as'=>'grupa.updateComments', 'uses'=>'GroupController@updateComments'));
Route::get('/grupa/compare', array('as'=>'grupa.compare', 'uses'=>'GroupCompareController@compare'));
Route::resource('/grupa', 'GroupController');
Route::post('/group/refreshRow', 'GroupController@refreshRow');
Route::get('/grupa/copy/{id}', array('as'=>'grupa.copy', 'uses'=>'GroupController@copy'));
Route::post('/grupa/copyStore', array('as'=>'grupa.copyStore', 'uses'=>'GroupController@copyStore'));
Route::get('/grupa/orderBy/{column}', array('as'=>'grupa.orderBy', 'uses'=>'GroupController@orderBy'));
Route::post('/grupa/hourSubtract', 'GroupController@hourSubtract');
Route::post('/grupa/hourAdd', 'GroupController@hourAdd');
Route::post('/grupa/change/{id}', 'GroupController@change');
Route::get('/grupa/{id}/{view?}', 'GroupController@show');
Route::post('/grupa/selectField', 'GroupController@selectField');
Route::post('/group/extension', 'GroupController@extension');

Route::post('/groupCompare/gradeChoice', 'GroupCompareController@gradeChoice');
Route::post('/groupCompare/displayGroup', 'GroupCompareController@displayGroup');

Route::get('/grupa_nauczyciele/automaticallyAddTeacher',  array('as'=>'grupa_nauczyciele.automaticallyAddTeacher', 'uses'=>'GroupTeacherController@automaticallyAddTeacher'));
Route::resource('/grupa_nauczyciele', 'GroupTeacherController');
Route::get('/grupa_nauczyciele/addTeacher/{group_id}', 'GroupTeacherController@addTeacher');
Route::post('/grupa_nauczyciele/changeTeacher', 'GroupTeacherController@changeTeacher');
Route::delete('/grupa_nauczyciele/{groupTeacher_id}', 'GroupTeacherController@destroy');

Route::resource('/grupa_klasy', 'GroupGradeController');
Route::get('/grupa_klasy/gradesList/{group_id}/{new?}', array('as'=>'grupa_klasy.gradesList', 'uses'=>'GroupGradeController@gradesList') );
Route::post('/grupa_klasy/addGrade', 'GroupGradeController@store');
Route::post('/grupa_klasy/removeGrade', 'GroupGradeController@removeGrade');
Route::delete('/grupa_klasy/{GroupGrade_id}', 'GroupGradeController@destroy');

Route::get('/groupStudent/orderBy/{column}', array('as'=>'groupStudent.orderBy', 'uses'=>'GroupStudentController@orderBy'));
Route::post('/groupStudent/refreshOtherGroupsInStudentsClass', 'GroupStudentController@refreshOtherGroupsInStudentsClass');
Route::post('/groupStudent/getStudentsFromGroup', 'GroupStudentController@getStudentsFromGroup' );
Route::post('/groupStudent/getOutsideGroupStudents', 'GroupStudentController@getOutsideGroupStudentsList' );
Route::post('/groupStudent/removeYesterday', 'GroupStudentController@removeYesterday' );
Route::post('/groupStudent/addStudent', 'GroupStudentController@addStudent');
Route::post('/groupStudent/addManyStudent', 'GroupStudentController@addManyStudent');

Route::get('/grupa_uczniowie/delete/{id}', array('as'=>'grupa_uczniowie.deleteForm', 'uses'=>'GroupStudentController@deleteForm'));
Route::post('/grupa_uczniowie/updateEnd/{id}', array('as'=>'grupa_uczniowie.updateEnd', 'uses'=>'GroupStudentController@updateEnd'));
Route::resource('/grupa_uczniowie', 'GroupStudentController');
Route::post('/grupa_uczniowie/getGroupStudents', 'GroupStudentController@getGroupStudents');
Route::post('/grupa_uczniowie/getAnotherTimeGroupStudents', 'GroupStudentController@getAnotherTimeGroupStudents');
Route::post('/grupa_uczniowie/getStudentGroups', 'GroupStudentController@getStudentGroups');
Route::delete('/grupa_uczniowie/delete/{id}', 'GroupStudentController@delete');
Route::get('/groupStudent/exportGroups', array('as'=>'groupStudent.exportGroups', 'uses'=>'GroupStudentExportController@groups'));


// -------------------------------------------------------------------------------------------------------- //
Route::resource('/godzina', 'LessonHourController');

Route::resource('/plan_lekcji', 'LessonPlanController');
Route::post('/lessonPlan/findGroupsToAssign', 'LessonPlanController@findGroupsToAssign');

Route::post('/lessonPlan/findGroupLessonForHour', 'LessonPlanController@findGroupLessonForHour');

Route::post('/lessonPlan/findClassroomLesson', 'LessonPlanController@findClassroomLesson');
Route::post('/lessonPlan/findLessonsWithoutClassroom', 'LessonPlanController@findLessonsWithoutClassroom');
Route::post('/lessonPlan/setTheEndDateOfTheLesson', 'LessonPlanController@setTheEndDateOfTheLesson');
Route::post('/lessonPlan/setClassroomToLesson', 'LessonPlanController@setClassroomToLesson');

Route::post('/lessonPlan/getDateEnd', 'LessonPlanController@getDateEnd');
Route::post('/lessonPlan/cloneLesson', 'LessonPlanController@cloneLesson');

Route::get('/lessonPlan/exportPlanForGrades/{dateView}', array('as'=>'lessonPlan.exportPlanForGrades', 'uses'=>'LessonPlanForGradesExportController@exportPlan'));
Route::get('/lessonPlan/exportPlanForGradesOldVersion/{dateView}', array('as'=>'lessonPlan.exportPlanForGradesOldVersion', 'uses'=>'LessonPlanForGradesExportControllerOldVersion@exportPlan'));
Route::get('/lessonPlan/exportPlanForTeachersAndClassrooms/{dateView}', array('as'=>'lessonPlan.exportPlanForTeachersAndClassrooms', 'uses'=>'LessonPlanForTeachersAndClassroomsExportController@exportPlan'));

Route::resource('/lekcja', 'LessonController');
Route::get('/lekcja/orderBy/{column}', array('as'=>'lekcja.orderBy', 'uses'=>'LessonController@orderBy'));


// -------------------------------------------------------------------------------------------------------- //
Route::resource('/zadanie', 'TaskController');
Route::post('/task/refreshRow', 'TaskController@refreshRow');
Route::get('/zadanie/orderBy/{column}', array('as'=>'zadanie.orderBy', 'uses'=>'TaskController@orderBy'));
Route::get('/zadanie/{id}/{view?}', 'TaskController@show');


Route::get('/polecenie/{id}/{view?}', 'CommandController@show');
Route::post('/polecenie/storeFromImport', array('as'=>'polecenie.storeFromImport', 'uses'=>'CommandExportController@storeFromImport'));
Route::resource('/polecenie', 'CommandController');
Route::post('/polecenie/refreshRow', 'CommandController@refreshRow');
Route::get('/polecenie/orderBy/{column}', array('as'=>'polecenie.orderBy', 'uses'=>'CommandController@orderBy'));
Route::get('/polecenie/taskCommandExport/{id}', array('as'=>'polecenie.taskCommandExport', 'uses'=>'CommandExportController@taskCommandExport'));
Route::get('/polecenie/taskCommandImport/{id}', array('as'=>'polecenie.taskCommandImport', 'uses'=>'CommandExportController@taskCommandImport'));
Route::get('/polecenie/import/{id}', array('as'=>'polecenie.import', 'uses'=>'CommandController@import'));

// ----- oceny zadań ----- //
Route::get('/ocena_zadania/createLot', array('as'=>'ocena_zadania.createLot', 'uses'=>'TaskRatingController@createLot'));
Route::post('/ocena_zadania/storeLot', array('as'=>'ocena_zadania.storeLot', 'uses'=>'TaskRatingController@storeLot'));
Route::get('/ocena_zadania/editLotTaskRatings', array('as'=>'ocena_zadania.editLotTaskRatings', 'uses'=>'TaskRatingController@editLotTaskRatings'));
Route::post('/ocena_zadania/updateLotTaskRatings', array('as'=>'ocena_zadania.updateLotTaskRatings', 'uses'=>'TaskRatingController@updateLotTaskRatings'));
Route::get('/ocena_zadania/editLotTerms', array('as'=>'ocena_zadania.editLotTerms', 'uses'=>'TaskRatingController@editLotTerms'));
Route::post('/ocena_zadania/updateLotTerms', array('as'=>'ocena_zadania.updateLotTerms', 'uses'=>'TaskRatingController@updateLotTerms'));
Route::get('/ocena_zadania/editLotImplementationDates', array('as'=>'ocena_zadania.editLotImplementationDates', 'uses'=>'TaskRatingController@editLotImplementationDates'));
Route::post('/ocena_zadania/updateLotImplementationDates', array('as'=>'ocena_zadania.updateLotImplementationDates', 'uses'=>'TaskRatingController@updateLotImplementationDates'));
Route::get('/ocena_zadania/editLotImportances', array('as'=>'ocena_zadania.editLotImportances', 'uses'=>'TaskRatingController@editLotImportances'));
Route::post('/ocena_zadania/updateLotImportances', array('as'=>'ocena_zadania.updateLotImportances', 'uses'=>'TaskRatingController@updateLotImportances'));
Route::get('/ocena_zadania/editLotPoints', array('as'=>'ocena_zadania.editLotPoints', 'uses'=>'TaskRatingController@editLotPoints'));
Route::post('/ocena_zadania/updateLotPoints', array('as'=>'ocena_zadania.updateLotPoints', 'uses'=>'TaskRatingController@updateLotPoints'));
Route::get('/ocena_zadania/editLotRatings', array('as'=>'ocena_zadania.editLotRatings', 'uses'=>'TaskRatingController@editLotRatings'));
Route::post('/ocena_zadania/updateLotRatings', array('as'=>'ocena_zadania.updateLotRatings', 'uses'=>'TaskRatingController@updateLotRatings'));
Route::get('/ocena_zadania/editLotRatingDates', array('as'=>'ocena_zadania.editLotRatingDates', 'uses'=>'TaskRatingController@editLotRatingDates'));
Route::post('/ocena_zadania/updateLotRatingDates', array('as'=>'ocena_zadania.updateLotRatingDates', 'uses'=>'TaskRatingController@updateLotRatingDates'));
Route::get('/ocena_zadania/editStudentRatings', array('as'=>'ocena_zadania.editStudentRatings', 'uses'=>'TaskRatingController@editStudentRatings'));
Route::post('/ocena_zadania/updateStudentRatings', array('as'=>'ocena_zadania.updateStudentRatings', 'uses'=>'TaskRatingController@updateStudentRatings'));
Route::post('/ocena_zadania/writeInTheDiary/{id}', 'TaskRatingController@writeInTheDiary');
Route::post('/ocena_zadania/removeFromDiary/{id}', 'TaskRatingController@removeFromDiary');
Route::post('/diaryYesNo/change/{value}', 'TaskRatingController@diaryYesNoChange');
Route::get('/ocena_zadania/improvement/{id}', array('as'=>'ocena_zadania.improvement', 'uses'=>'TaskRatingController@improvement'));
Route::post('/ocena_zadania/refreshRow', 'TaskRatingController@refreshRow');
Route::post('/ocena_zadania/refreshTable', 'TaskRatingController@refreshTable');
Route::resource('/ocena_zadania', 'TaskRatingController');
Route::get('/ocena_zadania/orderBy/{column}', array('as'=>'ocena_zadania.orderBy', 'uses'=>'TaskRatingController@orderBy'));

Route::get('/taskRatingImport/import/{id}', array('as'=>'taskRatingImport.import', 'uses'=>'TaskRatingImportController@import'));
Route::post('/taskRatingImport/store', array('as'=>'taskRatingImport.store', 'uses'=>'TaskRatingImportController@store'));
Route::post('/taskRatingImport/update', array('as'=>'taskRatingImport.update', 'uses'=>'TaskRatingImportController@update'));


// -------------------------------------------------------------------------------------------------------- //
Route::resource('/podrecznik', 'TextbookController');
Route::post('/textbook/refreshRow', 'TextbookController@refreshRow');
Route::get('/podrecznik/orderBy/{column}', array('as'=>'podrecznik.orderBy', 'uses'=>'TextbookController@orderBy'));
Route::get('/podrecznik/{id}/{view?}', 'TextbookController@show');

Route::post('/textbookChoice/refreshRow', 'TextbookChoiceController@refreshRow');
Route::post('/textbookChoice/prolongate/{id}', 'TextbookChoiceController@prolongate');
Route::post('/textbookChoice/verifyProlongate/{id}', 'TextbookChoiceController@verifyProlongate');
Route::post('/wybor_podrecznika/refreshTableForTextbook', 'TextbookChoiceController@refreshTableForTextbook');
Route::resource('/wybor_podrecznika', 'TextbookChoiceController');
Route::get('/wybor_podrecznika/orderBy/{column}', array('as'=>'wybor_podrecznika.orderBy', 'uses'=>'TextbookChoiceController@orderBy'));


// -------------------------------------------------------------------------------------------------------- //
Route::resource('/sesja', 'SessionController');
Route::get('/session/orderBy/{column}', array('as'=>'session.orderBy', 'uses'=>'SessionController@orderBy'));
Route::get('/sesja/{id}/{view?}', 'SessionController@show');
Route::post('/sesja/change/{id}', 'SessionController@change');
Route::post('/session/refreshRow', 'SessionController@refreshRow');

Route::resource('/opis_egzaminu', 'ExamDescriptionController');
Route::get('/examDescription/orderBy/{column}', array('as'=>'examDescription.orderBy', 'uses'=>'ExamDescriptionController@orderBy'));
Route::get('/opis_egzaminu/{id}/{view?}', 'ExamDescriptionController@show');
Route::post('/opis_egzaminu/change/{id}', 'ExamDescriptionController@change');
Route::post('/examDescription/refreshRow', 'ExamDescriptionController@refreshRow');

Route::post('/deklaracja/getListStudentWithoutDeclarationForGrade', 'DeclarationController@getListStudentWithoutDeclarationForGrade');
Route::get('/deklaracja/createForGrade', array('as'=>'deklaracja.createForGrade', 'uses'=>'DeclarationController@createForGrade'));
Route::post('/deklaracja/storeForGrade', array('as'=>'deklaracja.storeForGrade', 'uses'=>'DeclarationController@storeForGrade'));
Route::resource('/deklaracja', 'DeclarationController');
Route::get('/deklaracja/orderBy/{column}', array('as'=>'deklaracja.orderBy', 'uses'=>'DeclarationController@orderBy'));
Route::get('/deklaracja/change/{id}', 'DeclarationController@change');
Route::put('/deklaracja/updateExams/{id}', 'DeclarationController@updateExams');
Route::get('/deklaracja/{id}/{view?}', 'DeclarationController@show');
Route::post('/declaration/refreshRow', 'DeclarationController@refreshRow');
Route::post('/declaration/refreshForStudent', 'DeclarationController@refreshForStudent');

Route::resource('/termin', 'TermController');
Route::get('/termin/orderBy/{column}', array('as'=>'termin.orderBy', 'uses'=>'TermController@orderBy'));
Route::post('/termin/change/{id}', 'TermController@change');
Route::get('/termin/{id}/{view?}', 'TermController@show');

Route::post('/egzamin/refreshRow', 'ExamController@refreshRow');
Route::resource('/egzamin', 'ExamController');
Route::get('/egzamin/orderBy/{column}', array('as'=>'egzamin.orderBy', 'uses'=>'ExamController@orderBy'));
Route::post('/exam/addExamsForDeclaration', 'ExamController@addExamsForDeclaration');


// -------------------------------------------------------------------------------------------------------- //
Route::resource('/certificate', 'CertificateController');
Route::post('/certificate/refreshRow', 'CertificateController@refreshRow');
