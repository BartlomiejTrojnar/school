<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLessonHoursTable extends Migration
{
    public function up()
    {
        Schema::create('lesson_hours', function (Blueprint $table) {
            $table->increments('id');
            $table->string('day', 12);
            $table->tinyInteger('lesson_number')->unsigned();
            $table->time('start_time');
            $table->time('end_time');
        });
    }

    public function down()
    {
        Schema::dropIfExists('lesson_hours');
    }
}
