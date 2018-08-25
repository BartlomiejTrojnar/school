<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEnlargementsTable extends Migration
{
    public function up()
    {
        Schema::create('enlargements', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('student_id')->unsigned();
            $table->integer('subject_id')->unsigned();
            $table->string('language_level', 8)->nullable();
            $table->date('date_of_choice');
            $table->date('date_of_resignation')->nullable();
            $table->timestamps();
        });

        Schema::table('enlargements', function ($table) {
            $table->foreign('student_id')->references('id')->on('students')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('subject_id')->references('id')->on('subjects')->onUpdate('cascade');
        });
    }

    public function down()
    {
        Schema::table('enlargements', function ($table) {
            $table->dropForeign('enlargements_student_id_foreign');
            $table->dropForeign('enlargements_subject_id_foreign');
        });

        Schema::dropIfExists('enlargements');
    }
}