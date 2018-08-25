<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExamDescriptionsTable extends Migration
{
    public function up()
    {
        Schema::create('exam_descriptions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('session_id')->unsigned();
            $table->integer('subject_id')->unsigned();
            $table->enum('type', array('pisemny', 'ustny'))->default('pisemny');
            $table->enum('level', array('podstawowy', 'rozszerzony', 'nieokreślony'))->default('rozszerzony');
            $table->tinyInteger('max_points')->unsigned();
            $table->timestamps();
        });

       Schema::table('exam_descriptions', function ($table) {
            $table->foreign('session_id')->references('id')->on('sessions')->onUpdate('cascade');
            $table->foreign('subject_id')->references('id')->on('subjects')->onUpdate('cascade');
        });
     }

    public function down()
    {
        Schema::table('exam_descriptions', function ($table) {
            $table->dropForeign('exam_descriptions_session_id_foreign');
            $table->dropForeign('exam_descriptions_subject_id_foreign');
        });

        Schema::dropIfExists('exam_descriptions');
    }
}