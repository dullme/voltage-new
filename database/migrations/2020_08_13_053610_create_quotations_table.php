<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuotationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quotations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id');
            $table->integer('status')->default(\App\Enums\QuotationStatus::PROCESSING);
            $table->string('name');
            $table->integer('total_quantity')->nullable();

            $table->integer('layout_of_whip')->nullable();
            $table->integer('distance_between_poles')->nullable();
            $table->text('remarks')->nullable();
            $table->text('typical')->nullable();
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
        Schema::dropIfExists('quotations');
    }
}
