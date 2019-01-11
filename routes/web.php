<?php
Route::get('/', function () {
    session()->put('dateSession', date('Y-m-d'));
    return view('welcome');
});

Route::resource('/szkola', 'SchoolController');
Route::get('/szkola/sortuj/{column}', 'SchoolController@orderBy');
Route::get('/szkola/{id}/{view}', 'SchoolController@show');

Route::resource('/rok_szkolny', 'SchoolYearController');
Route::get('/rok_szkolny/{id}/change', 'SchoolYearController@change');
Route::get('/rok_szkolny/{id}/{view}', 'SchoolYearController@show');

Route::resource('/uczen', 'StudentController');
Route::get('/uczen/sortuj/{column}', 'StudentController@orderBy');
Route::get('/uczen/{id}/{view}', 'StudentController@show');

Route::resource('/ksiega_uczniow', 'BookOfStudentController');
Route::get('/ksiega_uczniow/sortuj/{column}', 'BookOfStudentController@orderBy');

Route::resource('/klasa', 'GradeController');
Route::get('/klasa/sortuj/{column}', 'GradeController@orderBy');
Route::get('/klasa/{id}/getDates', 'GradeController@getDates');
Route::get('/klasa/{id}/{view}', 'GradeController@show');

Route::resource('/klasy_ucznia', 'StudentClassController');
Route::get('/klasy_ucznia/sortuj/{column}', 'StudentClassController@orderBy');

// -------------------------------------------------------------------------------------------------------- //
Route::resource('/zadanie', 'TaskController');
Route::get('/zadanie/sortuj/{column}', 'TaskController@orderBy');
Route::get('/zadanie/{id}/{view}', 'TaskController@show');

Route::resource('/polecenie', 'CommandController');
Route::get('/polecenie/sortuj/{column}', 'CommandController@orderBy');
Route::get('/polecenie/export/{id}', 'CommandController@export')->name('polecenie.export');
Route::get('/polecenie/import/{id}', 'CommandController@import')->name('polecenie.import');


// -------------------------------------------------------------------------------------------------------- //

Route::resource('/godzina', 'LessonHourController');


Route::resource('/sala', 'ClassroomController');
Route::get('/sala/sortuj/{column}', 'ClassroomController@orderBy');
Route::get('/sala/{id}/{view}', 'ClassroomController@show');

Route::resource('/przedmiot', 'SubjectController');
Route::get('/przedmiot/sortuj/{column}', 'SubjectController@orderBy');
Route::get('/przedmiot/{id}/{view}', 'SubjectController@show');




Route::get('/nauczyciel/sortuj/{column}', 'TeacherController@orderBy');
Route::resource('/nauczyciel', 'TeacherController');
Route::get('/nauczyciel/{id}/{view}', 'TeacherController@show');

Route::post('/nauczany_przedmiot/add', 'TaughtSubjectController@add');
Route::resource('/nauczany_przedmiot', 'TaughtSubjectController');
Route::get('/nauczany_przedmiot/sortuj/{column}', 'TaughtSubjectController@orderBy');
Route::get('/nauczany_przedmiot/delete/{id}', 'TaughtSubjectController@delete');


Route::resource('/rozszerzenie', 'EnlargementController');
Route::get('/rozszerzenie/sortuj/{column}', 'EnlargementController@orderBy');

Route::resource('/grupa', 'GroupController');
Route::get('/grupa/sortuj/{column}', 'GroupController@orderBy');
Route::get('/grupa/hourSubtract/{id}', 'GroupController@hourSubtract');
Route::get('/grupa/hourAdd/{id}', 'GroupController@hourAdd');
Route::get('/grupa/{id}/{view}', 'GroupController@show');

Route::get('/grupa_klasy/addGrade/{id}', 'GroupClassController@addGrade');
Route::resource('/grupa_klasy', 'GroupClassController');

Route::get('/grupa_nauczyciele/addTeacher/{group_id}', 'GroupTeacherController@addTeacher');
Route::get('/grupa_nauczyciele/sortuj/{column}', 'GroupTeacherController@orderBy');
Route::resource('/grupa_nauczyciele', 'GroupTeacherController');

Route::resource('/grupa_uczniowie', 'GroupStudentController');
Route::get('/grupa_uczniowie/sortuj/{column}', 'GroupStudentController@orderBy');

Route::get('/plan_lekcji/addLesson/{group_id}/{hour_id}', 'LessonPlanController@addLesson');
Route::get('/plan_lekcji/findLesson/{group_id}/{hour_id}', 'LessonPlanController@findLesson');
Route::resource('/plan_lekcji', 'LessonPlanController');
Route::get('/plan_lekcji/sortuj/{column}', 'LessonPlanController@orderBy');

Route::resource('/lekcja', 'LessonController');
Route::get('/lekcja/sortuj/{column}', 'LessonController@orderBy');


Route::resource('/ocena_zadania', 'TaskRatingController');
Route::get('/ocena_zadania/sortuj/{column}', 'TaskRatingController@orderBy');
Route::resource('/ocena_polecenia', 'CommandRatingController');
Route::get('/ocena_polecenia/sortuj/{column}', 'CommandRatingController@orderBy');

Route::resource('/wzor_swiadectwa', 'CertificatePatternController');
Route::get('/wzor_swiadectwa/sortuj/{column}', 'CertificatePatternController@orderBy');
Route::resource('/swiadectwo', 'CertificateController');
Route::get('/swiadectwo/sortuj/{column}', 'CertificateController@orderBy');
Route::resource('/osiagniecie', 'AchievementController');
Route::get('/osiagniecie/sortuj/{column}', 'AchievementController@orderBy');

Route::resource('/podrecznik', 'TextbookController');
Route::get('/podrecznik/sortuj/{column}', 'TextbookController@orderBy');
Route::resource('/wybor_podrecznika', 'TextbookChoiceController');
Route::get('/wybor_podrecznika/sortuj/{column}', 'TextbookChoiceController@orderBy');

/* ---------------------------------------------------------------------------------------------- */
Route::resource('/sesja', 'SessionController');
Route::get('/sesja/sortuj/{column}', 'SessionController@orderBy');
Route::get('/sesja/{id}/{view}', 'SessionController@show');

Route::resource('/deklaracja', 'DeclarationController');
Route::get('/deklaracja/sortuj/{column}', 'DeclarationController@orderBy');
Route::get('/deklaracja/{id}/{view}', 'DeclarationController@show');

Route::resource('/opis_egzaminu', 'ExamDescriptionController');
Route::get('/opis_egzaminu/sortuj/{column}', 'ExamDescriptionController@orderBy');
Route::get('/opis_egzaminu/{id}/{view}', 'ExamDescriptionController@show');

Route::resource('/termin', 'TermController');
Route::get('/termin/sortuj/{column}', 'TermController@orderBy');
Route::get('/termin/{id}/{view}', 'TermController@show');

Route::resource('/egzamin', 'ExamController');
Route::get('/egzamin/sortuj/{column}', 'ExamController@orderBy');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
