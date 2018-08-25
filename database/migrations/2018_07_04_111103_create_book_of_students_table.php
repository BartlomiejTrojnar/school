<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookOfStudentsTable extends Migration
{
    public function up()
    {
        Schema::create('book_of_students', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('school_id')->unsigned();
            $table->integer('student_id')->unsigned();
            $table->integer('number')->unsigned();
            $table->timestamps();
        });

        Schema::table('book_of_students', function ($table) {
            $table->foreign('school_id')->references('id')->on('schools')->onUpdate('cascade');
            $table->foreign('student_id')->references('id')->on('students')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('book_of_students', function ($table) {
            $table->dropForeign('book_of_students_school_id_foreign');
            $table->dropForeign('book_of_students_student_id_foreign');
        });

        Schema::dropIfExists('book_of_students');
    }
}