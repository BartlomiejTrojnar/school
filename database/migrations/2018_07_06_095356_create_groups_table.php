<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupsTable extends Migration
{
    public function up()
    {
        Schema::create('groups', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('subject_id')->unsigned();
            $table->date('date_start');
            $table->date('date_end');
            $table->string('comments', 30)->nullable();
            $table->enum('level', array('nieokreślony', 'podstawowy', 'rozszerzony'))->nullable();
            $table->tinyInteger('hours')->unsigned()->nullable();
            $table->timestamps();
        });

        Schema::table('groups', function ($table) {
            $table->foreign('subject_id')->references('id')->on('subjects')->onUpdate('cascade');
        });
    }

    public function down()
    {
        Schema::table('groups', function ($table) {
            $table->dropForeign('groups_subject_id_foreign');
        });

        Schema::dropIfExists('groups');
    }
}
