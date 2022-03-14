<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubjectsTable extends Migration
{
    public function up()
    {
        Schema::create('subjects', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 60);
            $table->string('short_name', 15)->nullable();
            $table->boolean('actual')->default(0);
            $table->tinyInteger('order_in_the_sheet')->nullable();
            $table->boolean('expanded')->default(0);
        });
    }

    public function down()
    {
        Schema::dropIfExists('subjects');
    }
}
