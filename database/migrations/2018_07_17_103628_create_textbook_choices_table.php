<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTextbookChoicesTable extends Migration
{
    public function up()
    {
        Schema::create('textbook_choices', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('textbook_id')->unsigned();
            $table->integer('school_id')->unsigned();
            $table->integer('school_year_id')->unsigned();
            $table->tinyInteger('learning_year')->unsigned();
            $table->enum('level', array('podstawowy', 'rozszerzony', 'nieokreślony'))->nullable();
            $table->timestamps();
        });

        Schema::table('textbook_choices', function ($table) {
            $table->foreign('textbook_id')->references('id')->on('textbooks')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('school_id')->references('id')->on('schools')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('school_year_id')->references('id')->on('school_years')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('textbook_choices', function ($table) {
            $table->dropForeign('textbook_choices_textbook_id_foreign');
            $table->dropForeign('textbook_choices_school_id_foreign');
            $table->dropForeign('textbook_choices_school_year_id_foreign');
        });

        Schema::dropIfExists('textbook_choices');
    }
}