<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAchievementsTable extends Migration
{
    public function up()
    {
        Schema::create('achievements', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('certificate_id')->unsigned();
            $table->string('inscription', 200);
            $table->timestamps();
        });

        Schema::table('achievements', function ($table) {
            $table->foreign('certificate_id')->references('id')->on('certificates')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('achievements', function ($table) {
            $table->dropForeign('achievements_certificate_id_foreign');
        });

        Schema::dropIfExists('achievements');
    }
}