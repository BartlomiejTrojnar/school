<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentGradesTable extends Migration
{
    public function up()
    {
        Schema::create('student_grades', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('student_id')->unsigned();
            $table->integer('grade_id')->unsigned();
            $table->date('date_start');
            $table->date('date_end');
            $table->tinyInteger('number');
            $table->string('comments', 32)->nullable();
            $table->boolean('confirmation_date_start');
            $table->boolean('confirmation_date_end');
            $table->boolean('confirmation_numer');
            $table->boolean('confirmation_comments');
            $table->timestamps();
        });

        Schema::table('student_grades', function ($table) {
            $table->foreign('student_id')->references('id')->on('students')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('grade_id')->references('id')->on('grades')->onUpdate('cascade');
        });
    }

    public function down()
    {
        Schema::table('student_grades', function ($table) {
            $table->dropForeign('student_grades_student_id_foreign');
            $table->dropForeign('student_grades_grade_id_foreign');
        });

        Schema::dropIfExists('student_grades');
    }
}
