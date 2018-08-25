<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLessonPlansTable extends Migration
{
    public function up()
    {
        Schema::create('lesson_plans', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('group_id')->unsigned();
            $table->integer('lesson_hour_id')->unsigned();
            $table->integer('classroom_id')->unsigned()->nullable();
            $table->date('date_start');
            $table->date('date_end');
            $table->timestamps();
        });

        Schema::table('lesson_plans', function ($table) {
            $table->foreign('group_id')->references('id')->on('groups')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('lesson_hour_id')->references('id')->on('lesson_hours')->onUpdate('cascade');
            $table->foreign('classroom_id')->references('id')->on('classrooms')->onUpdate('cascade')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('lesson_plans', function ($table) {
            $table->dropForeign('lesson_plans_group_id_foreign');
            $table->dropForeign('lesson_plans_lesson_hour_id_foreign');
            $table->dropForeign('lesson_plans_classroom_id_foreign');
        });

        Schema::dropIfExists('lesson_plans');
    }
}