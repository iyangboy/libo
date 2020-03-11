<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductByStagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_by_stages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('product_id')->comment('商品ID');
            $table->string('type')->default('by_stage')->comment('类型');
            $table->string('value')->comment('规格值');
            $table->decimal('price', 10, 2)->comment('服务费');
            $table->decimal('interest_rate', 10, 4)->comment('日利率');
            $table->decimal('interest_fine_rate', 10, 4)->comment('逾期日利率');
            $table->unsignedInteger('stock')->default(0)->comment('库存');
            $table->boolean('on_sale')->default(true)->comment('是否上线');

            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
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
        Schema::dropIfExists('product_by_stages');
    }
}
