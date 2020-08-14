<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_infos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('solar_panel_id');
            $table->unsignedBigInteger('bracket_id');
            $table->integer('string');
            $table->integer('style')->comment('摆放方式');
            $table->string('connector');
            $table->integer('fuse');
            $table->integer('junction_box_to_rack1');
            $table->string('junction_box_to_rack1_remark');
            $table->integer('junction_box_to_rack2')->nullable();
            $table->string('junction_box_to_rack2_remark')->nullable();
            $table->integer('of_module_per_string');
            $table->integer('end_of_extender');
            $table->integer('module_to_module_extender');
            $table->string('number_of_string');
            $table->string('typical');
            $table->text('remarks');
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
        Schema::dropIfExists('project_infos');
    }
}
