<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEdgesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('edges', function (Blueprint $table) {
            $table->integer('start')->unsigned();
            $table->integer('end')->unsigned();
            $table->longText('types');
            $table->primary(['start', 'end']);
            $table->foreign('start')->references('id')->on('nodes')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('end')->references('id')->on('nodes')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('edges');
    }
}
