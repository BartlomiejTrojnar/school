<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLessonsTable extends Migration
{
    public function up()
    {
        Schema::create('lessons', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('group_id')->unsigned();
            $table->integer('teacher_id')->unsigned();
            $table->date('lesson_date');
            $table->integer('lesson_length')->unsigned();
            $table->integer('number')->unsigned()->nullable();
            $table->string('topic_entered', 45)->nullable();
            $table->string('topic_realized', 45)->nullable();
            $table->string('comments', 45)->nullable();
            $table->timestamps();
        });

        Schema::table('lessons', function ($table) {
            $table->foreign('group_id')->references('id')->on('groups')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('teacher_id')->references('id')->on('teachers')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('lessons', function ($table) {
            $table->dropForeign('lessons_group_id_foreign');
            $table->dropForeign('lessons_teacher_id_foreign');
        });

        Schema::dropIfExists('lessons');
    }
}