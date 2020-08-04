<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTypicalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('typicals', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('show_name');
            $table->integer('version');
            $table->text('harnesses_selected');
            $table->text('margin');
            $table->text('motors');
            $table->text('fuse');
            $table->text('nofuse');
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
        Schema::dropIfExists('typicals');
    }
}
