<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentsTable extends Migration
{
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->increments('id');
            $table->string('first_name', 20);
            $table->string('second_name', 12)->nullable();
            $table->string('last_name', 18);
            $table->string('family_name', 15)->nullable();
            $table->enum('sex', array('kobieta', 'mężczyzna'));
            $table->string('PESEL', 11)->nullable();
            $table->string('place_of_birth', 20)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('students');
    }
}
