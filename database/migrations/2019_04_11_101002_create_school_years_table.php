<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSchoolYearsTable extends Migration
{
    public function up()
    {
        Schema::create('school_years', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date_start');
            $table->date('date_end');
            $table->date('date_of_classification_of_the_last_grade')->nullable();
            $table->date('date_of_graduation_of_the_last_grade')->nullable();
            $table->date('date_of_classification')->nullable();
            $table->date('date_of_graduation')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('school_years');
    }
}
