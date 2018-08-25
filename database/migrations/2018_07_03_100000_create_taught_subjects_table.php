<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaughtSubjectsTable extends Migration
{
    public function up()
    {
        Schema::create('taught_subjects', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('teacher_id')->unsigned();
            $table->integer('subject_id')->unsigned();
        });

        Schema::table('taught_subjects', function ($table) {
            $table->foreign('teacher_id')->references('id')->on('teachers')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('subject_id')->references('id')->on('subjects')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('taught_subjects', function ($table) {
            $table->dropForeign('taught_subjects_teacher_id_foreign');
            $table->dropForeign('taught_subjects_subject_id_foreign');
        });

        Schema::dropIfExists('taught_subjects');
    }
}
