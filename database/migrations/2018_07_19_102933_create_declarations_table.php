<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeclarationsTable extends Migration
{
    public function up()
    {
        Schema::create('declarations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('student_id')->unsigned();
            $table->integer('session_id')->unsigned();
            $table->tinyInteger('application_number')->unsigned()->default(1);
            $table->string('student_code', 3)->nullable();
            $table->timestamps();
        });

        Schema::table('declarations', function ($table) {
            $table->foreign('student_id')->references('id')->on('students')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('session_id')->references('id')->on('sessions')->onUpdate('cascade');
        });
    }

    public function down()
    {
        Schema::table('declarations', function ($table) {
            $table->dropForeign('declarations_student_id_foreign');
            $table->dropForeign('declarations_session_id_foreign');
        });

        Schema::dropIfExists('declarations');
    }
}