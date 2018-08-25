<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExamsTable extends Migration
{
    public function up()
    {
        Schema::create('exams', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('declaration_id')->unsigned();
            $table->integer('exam_description_id')->unsigned();
            $table->integer('term_id')->unsigned()->nullable();
            $table->enum('exam_type', array('obowiązkowy', 'dodatkowy'));
            $table->float('points')->nullable();
            $table->string('comments', 15)->nullable();
            $table->timestamps();
        });

        Schema::table('exams', function ($table) {
            $table->foreign('declaration_id')->references('id')->on('declarations')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('exam_description_id')->references('id')->on('exam_descriptions')->onUpdate('cascade');
            $table->foreign('term_id')->references('id')->on('terms')->onUpdate('cascade');
        });
    }

    public function down()
    {
        Schema::table('exams', function ($table) {
            $table->dropForeign('exams_declaration_id_foreign');
            $table->dropForeign('exams_exam_description_id_foreign');
            $table->dropForeign('exams_term_id_foreign');
        });

        Schema::dropIfExists('exams');
    }
}