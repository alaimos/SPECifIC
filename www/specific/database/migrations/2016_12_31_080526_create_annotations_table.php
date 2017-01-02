<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnnotationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('annotations', function (Blueprint $table) {
            $table->integer('term_id')->unsigned();
            $table->integer('node_id')->unsigned();
            $table->primary(['term_id', 'node_id']);
            $table->foreign('term_id')->references('id')->on('annotation_terms')
                ->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('node_id')->references('id')->on('nodes')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('annotations');
    }
}
