<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHarnessComponentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('harness_components', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('harness_id');
            $table->unsignedBigInteger('component_id');
            $table->unsignedTinyInteger('part_type')->comment('零件类型');
            $table->unsignedDecimal('length', 10, 2)->nullable()->comment('长度/ft');
            $table->unsignedInteger('quantity')->comment('数量');
            $table->text('details')->comment('详情');
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
        Schema::dropIfExists('harness_components');
    }
}
