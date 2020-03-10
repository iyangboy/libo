<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductSkusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_skus', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->text('specification')->nullable()->comment('规格');
            $table->decimal('price', 10, 2)->comment('服务费');
            $table->decimal('loan_limit', 10, 2)->comment('贷款限额');
            $table->decimal('interest_rate', 10, 4)->comment('日利率');
            $table->unsignedInteger('stock')->default(0)->comment('库存');
            $table->boolean('on_sale')->default(true)->comment('是否上线');

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
        Schema::dropIfExists('product_skus');
    }
}
