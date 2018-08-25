<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupStudentsTable extends Migration
{
    public function up()
    {
        Schema::create('group_students', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('group_id')->unsigned();
            $table->integer('student_id')->unsigned();
            $table->date('date_start');
            $table->date('date_end');
            $table->enum('mid-year_rating', array('1', '2', '3', '4', '5', '6', 'nieklasyfikowany/a', 'zwolniony/a'))->nullable();
            $table->enum('final_rating', array('1', '2', '3', '4', '5', '6', 'nieklasyfikowany/a', 'zwolniony/a'))->nullable();
            $table->timestamps();
        });

        Schema::table('group_students', function ($table) {
            $table->foreign('group_id')->references('id')->on('groups')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('student_id')->references('id')->on('students')->onUpdate('cascade');
        });
    }

    public function down()
    {
        Schema::table('group_students', function ($table) {
            $table->dropForeign('group_students_student_id_foreign');
            $table->dropForeign('group_students_group_id_foreign');
        });

        Schema::dropIfExists('group_students');
    }
}
