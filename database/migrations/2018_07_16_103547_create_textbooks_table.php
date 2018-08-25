<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTextbooksTable extends Migration
{
    public function up()
    {
        Schema::create('textbooks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('subject_id')->unsigned();
            $table->string('author', 75)->nullable();
            $table->string('title', 125);
            $table->string('publishing_house', 30)->nullable();
            $table->string('admission', 18)->nullable();
            $table->string('comments', 60)->nullable();
            $table->timestamps();
        });

        Schema::table('textbooks', function ($table) {
            $table->foreign('subject_id')->references('id')->on('subjects')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('textbooks', function ($table) {
            $table->dropForeign('textbooks_subject_id_foreign');
        });

        Schema::dropIfExists('textbooks');
    }
}