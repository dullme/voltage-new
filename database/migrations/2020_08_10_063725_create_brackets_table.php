<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBracketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('brackets', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique()->comment('支架种类名称');
            $table->integer('type')->comment('支架类型 固定 跟踪');
            $table->integer('style')->comment('排列方式 单排 双排');
            $table->unsignedInteger('driver')->nullable()->comment('Driver 当类型为跟踪支架时必填');
            $table->string('file')->nullable()->comment('文件');
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
        Schema::dropIfExists('brackets');
    }
}
