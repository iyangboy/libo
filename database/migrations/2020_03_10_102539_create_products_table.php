<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('admin_user_id')->comment('管理员');
            $table->foreign('admin_user_id')->references('id')->on('admin_users');
            $table->string('title')->comment('名称');
            $table->string('long_title')->nullable()->comment('长名称');
            $table->longText('description')->comment('描述');
            $table->string('image')->comment('图片');
            $table->boolean('on_sale')->default(true)->comment('是否上线');
            $table->float('rating')->default(5)->comment('评分');
            $table->unsignedInteger('sold_count')->default(0)->comment('销量');
            $table->unsignedInteger('review_count')->default(0)->comment('评价数量');
            $table->decimal('price', 10, 2)->comment('SKU 最低服务费');
            $table->decimal('loan_limit', 10, 2)->comment('贷款限额');
            $table->decimal('interest_rate', 10, 4)->comment('日利率');
            $table->text('specification')->nullable()->comment('规格');
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
        Schema::dropIfExists('products');
    }
}
