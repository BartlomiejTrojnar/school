<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommandRatingsTable extends Migration
{
    public function up()
    {
        Schema::create('command_ratings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('command_id')->unsigned();
            $table->integer('task_rating_id')->unsigned();
            $table->tinyInteger('points')->unsigned()->nullable();
            $table->timestamps();
        });

        Schema::table('command_ratings', function ($table) {
            $table->foreign('command_id')->references('id')->on('commands')->onUpdate('cascade');
            $table->foreign('task_rating_id')->references('id')->on('task_ratings')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('command_ratings', function ($table) {
            $table->dropForeign('command_ratings_command_id_foreign');
            $table->dropForeign('command_ratings_task_rating_id_foreign');
        });

        Schema::dropIfExists('command_ratings');
    }
}