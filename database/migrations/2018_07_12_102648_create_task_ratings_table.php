<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaskRatingsTable extends Migration
{
    public function up()
    {
        Schema::create('task_ratings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('student_id')->unsigned();
            $table->integer('task_id')->unsigned();
            $table->dateTime('deadline');
            $table->date('implementation_date')->nullable();
            $table->tinyInteger('version')->unsigned()->default('1');
            $table->float('importance')->unsigned()->default('1.0');
            $table->dateTime('rating_date')->nullable;
            $table->float('points')->unsigned()->nullable();
            $table->string('rating', 2)->nullable();
            $table->string('comments', 50)->nullable();
            $table->boolean('diary')->default(false);
            $table->dateTime('entry_date')->nullable();
            $table->timestamps();
        });

        Schema::table('task_ratings', function ($table) {
            $table->foreign('student_id')->references('id')->on('students')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('task_id')->references('id')->on('tasks')->onUpdate('cascade');
        });
    }

    public function down()
    {
        Schema::table('task_ratings', function ($table) {
            $table->dropForeign('task_ratings_student_id_foreign');
            $table->dropForeign('task_ratings_task_id_foreign');
        });

        Schema::dropIfExists('task_ratings');
    }
}