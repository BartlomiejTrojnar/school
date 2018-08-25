<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupClassesTable extends Migration
{
    public function up()
    {
        Schema::create('group_classes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('group_id')->unsigned();
            $table->integer('class_id')->unsigned();
            $table->timestamps();
        });

        Schema::table('group_classes', function ($table) {
            $table->foreign('group_id')->references('id')->on('groups')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('class_id')->references('id')->on('grades')->onUpdate('cascade');
        });
    }

    public function down()
    {
        Schema::table('group_classes', function ($table) {
            $table->dropForeign('group_classes_group_id_foreign');
            $table->dropForeign('group_classes_class_id_foreign');
        });

        Schema::dropIfExists('group_classes');
    }
}
