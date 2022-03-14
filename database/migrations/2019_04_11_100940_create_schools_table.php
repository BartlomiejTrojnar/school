<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSchoolsTable extends Migration
{
    public function up()
    {
        Schema::create('schools', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 40);
            $table->string('id_OKE', 12)->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('schools');
    }
}
