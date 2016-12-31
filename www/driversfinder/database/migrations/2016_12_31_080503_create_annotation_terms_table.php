<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnnotationTermsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('annotation_terms', function (Blueprint $table) {
            $table->increments('id');
            $table->string('accession')->index();
            $table->text('description');
            $table->string('source_id')->index();
            $table->foreign('source_id')->references('id')->on('annotation_sources')
                ->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('annotation_terms');
    }
}
