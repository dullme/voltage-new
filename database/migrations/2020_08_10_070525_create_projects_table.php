<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id')->unsigned()->comment('公司ID');
            $table->string('code')->unique()->comment('项目编号');
            $table->string('name')->unique()->comment('项目名称');
            $table->string('address')->nullable()->comment('项目地址');
            $table->integer('total_quantity')->nullable();
            $table->decimal('size_dc', 10, 3)->nullable();

            $table->integer('layout_of_whip')->nullable();
            $table->integer('distance_between_poles')->nullable();
            $table->text('remarks')->nullable();

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
        Schema::dropIfExists('projects');
    }
}
