<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommandsTable extends Migration
{
    public function up()
    {
        Schema::create('commands', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('task_id')->unsigned();
            $table->integer('number')->unsigned();
            $table->string('command', 25);
            $table->string('description', 65)->nullable();
            $table->tinyInteger('points')->default(1);
            $table->timestamps();
        });

        Schema::table('commands', function ($table) {
            $table->foreign('task_id')->references('id')->on('tasks')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('commands', function ($table) {
            $table->dropForeign('commands_task_id_foreign');
        });

        Schema::dropIfExists('commands');
    }
}