<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComponentCombsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('component_combs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('line_id');
            $table->unsignedBigInteger('male_id')->nullable();
            $table->unsignedBigInteger('female_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('component_combs');
    }
}
