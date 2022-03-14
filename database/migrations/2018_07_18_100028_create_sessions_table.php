<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSessionsTable extends Migration
{
    public function up()
    {
        Schema::create('sessions', function (Blueprint $table) {
            $table->increments('id');
            $table->smallInteger('year');
            $table->enum('type', array('maj', 'sierpien', 'styczen'));
        });
    }

    public function down()
    {
        Schema::dropIfExists('sessions');
    }
}
