<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCertificatesTable extends Migration
{
    public function up()
    {
        Schema::create('certificates', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('student_id')->unsigned();
            $table->integer('sheet_pattern_id')->unsigned();
            $table->integer('certificate_pattern_id')->unsigned();
            $table->date('date_of_council');
            $table->date('date_of_release');
            $table->timestamps();
        });

        Schema::table('certificates', function ($table) {
            $table->foreign('student_id')->references('id')->on('students')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('sheet_pattern_id', 'certificates_sheet_pattern_id_foreign')->references('id')->on('certificate_patterns')->onUpdate('cascade');
            $table->foreign('certificate_pattern_id', 'certificates_certificate_pattern_id_foreign')->references('id')->on('certificate_patterns')->onUpdate('cascade');
        });
    }

    public function down()
    {
        Schema::table('certificates', function ($table) {
            $table->dropForeign('certificates_student_id_foreign');
            $table->dropForeign('certificates_sheet_pattern_id_foreign');
            $table->dropForeign('certificates_certificate_pattern_id_foreign');
        });

        Schema::dropIfExists('certificates');
    }
}
