<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupTeachersTable extends Migration
{
    public function up()
    {
        Schema::create('group_teachers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('group_id')->unsigned();
            $table->integer('teacher_id')->unsigned();
            $table->date('date_start');
            $table->date('date_end');
            $table->timestamps();
        });

        Schema::table('group_teachers', function ($table) {
            $table->foreign('group_id')->references('id')->on('groups')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('teacher_id')->references('id')->on('teachers')->onUpdate('cascade');
        });
    }

    public function down()
    {
        Schema::table('group_teachers', function ($table) {
            $table->dropForeign('group_teachers_teacher_id_foreign');
            $table->dropForeign('group_teachers_group_id_foreign');
        });

        Schema::dropIfExists('group_teachers');
    }
}
