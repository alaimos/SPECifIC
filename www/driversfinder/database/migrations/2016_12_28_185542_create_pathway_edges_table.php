<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePathwayEdgesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pathway_edges', function (Blueprint $table) {
            $table->integer('pathway_id')->unsigned();
            $table->integer('edge_start')->unsigned();
            $table->integer('edge_end')->unsigned();
            $table->primary(['pathway_id', 'edge_start', 'edge_end']);
            $table->foreign('pathway_id')->references('id')->on('pathways')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('edge_start')->references('start')->on('edges')
                ->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('edge_end')->references('end')->on('edges')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pathway_edges');
    }
}
