<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeachersTable extends Migration
{
    public function up()
    {
        Schema::create('teachers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('first_name', 16)->nullable();
            $table->string('last_name', 18);
            $table->string('family_name', 15)->nullable()->default(NULL);
            $table->string('short', 2)->nullable()->default(NULL);
            $table->string('degree', 10)->nullable()->default(NULL);
            $table->integer('classroom_id')->unsigned()->nullable()->default(NULL);
            $table->integer('first_year_id')->unsigned()->nullable()->default(NULL);
            $table->integer('last_year_id')->unsigned()->nullable()->default(NULL);
            $table->tinyInteger('order')->unsigned()->default(10);
            $table->timestamps();
        });

        Schema::table('teachers', function ($table) {
            $table->foreign('classroom_id')->references('id')->on('classrooms')->onUpdate('cascade')->onDelete('set null');
            $table->foreign('first_year_id', 'teachers_first_year_foreign')->references('id')->on('school_years')->onUpdate('cascade');
            $table->foreign('last_year_id', 'teachers_last_year_foreign')->references('id')->on('school_years')->onUpdate('cascade');
        });
    }

    public function down()
    {
        Schema::table('teachers', function ($table) {
            $table->dropForeign('teachers_classroom_id_foreign');
            $table->dropForeign('teachers_first_year_foreign');
            $table->dropForeign('teachers_last_year_foreign');
        });

        Schema::dropIfExists('teachers');
    }
}
