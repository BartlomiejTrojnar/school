<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGradesTable extends Migration
{
    public function up()
    {
        Schema::create('grades', function (Blueprint $table) {
            $table->increments('id');
            $table->smallInteger('year_of_beginning')->unsigned();
            $table->smallInteger('year_of_graduation')->unsigned();
            $table->string('symbol', 1)->nullable();
            $table->integer('school_id')->unsigned();
            $table->timestamps();
        });

        Schema::table('grades', function ($table) {
            $table->foreign('school_id')->references('id')->on('schools')->onUpdate('cascade');
        });
    }

    public function down()
    {
        Schema::table('grades', function ($table) {
            $table->dropForeign('grades_school_id_foreign');
        });

        Schema::dropIfExists('grades');
    }
}
