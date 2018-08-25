<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCertificatePatternsTable extends Migration
{
    public function up()
    {
        Schema::create('certificate_patterns', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 15);
            $table->string('destiny', 40)->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('certificate_patterns');
    }
}
