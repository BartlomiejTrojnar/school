<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClassroomsTable extends Migration
{
    public function up()
    {
        Schema::create('classrooms', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 20);
            $table->tinyInteger('capacity')->unsigned();
            $table->tinyInteger('floor')->unsigned();
            $table->tinyInteger('line')->unsigned();
            $table->tinyInteger('column')->unsigned();
        });
    }

    public function down()
    {
        Schema::dropIfExists('classrooms');
    }
}
