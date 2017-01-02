<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePerturbationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('perturbations', function (Blueprint $table) {
            $table->integer('disease_id')->unsigned();
            $table->integer('node_id')->unsigned();
            $table->double('perturbation');
            $table->double('pvalue');
            $table->primary(['disease_id', 'node_id']);
            $table->foreign('disease_id')->references('id')->on('diseases')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('perturbations');
    }
}
