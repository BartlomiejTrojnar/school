<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTermsTable extends Migration
{
    public function up()
    {
        Schema::create('terms', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('exam_description_id')->unsigned();
            $table->integer('classroom_id')->unsigned()->nullable();
            $table->dateTime('date_start');
            $table->dateTime('date_end');
            $table->timestamps();
        });

        Schema::table('terms', function ($table) {
            $table->foreign('exam_description_id')->references('id')->on('exam_descriptions')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('classroom_id')->references('id')->on('classrooms')->onUpdate('cascade');
        });
    }

    public function down()
    {
        Schema::table('terms', function ($table) {
            $table->dropForeign('terms_exam_description_id_foreign');
            $table->dropForeign('terms_classroom_id_foreign');
        });

        Schema::dropIfExists('terms');
    }
}