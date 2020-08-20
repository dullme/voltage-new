<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blocks', function (Blueprint $table) {
            $table->id();
            $table->integer('quotation_id')->unsigned()->comment('报价单ID');
            $table->string('name')->comment('名称');
            $table->integer('reference');
            $table->integer('order')->unsigned()->default(0)->comment('排序');
            $table->mediumText('block')->comment('内容');
            $table->text('total_typical')->comment('合计');
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
        Schema::dropIfExists('blocks');
    }
}
