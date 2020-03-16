<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCreditLineProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('credit_line_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('admin_user_id')->nullable()->comment('管理员');
            $table->foreign('admin_user_id')->references('id')->on('admin_users');
            $table->string('title')->comment('名称');
            $table->string('long_title')->nullable()->comment('长名称');
            $table->longText('description')->comment('描述');
            $table->string('image')->comment('图片');
            $table->boolean('on_sale')->default(true)->comment('是否上线');
            $table->unsignedInteger('sold_count')->default(0)->comment('销量');
            $table->decimal('market_price', 10, 2)->comment('市场价');
            $table->decimal('price', 10, 2)->comment('售价');
            $table->decimal('quota_min', 10, 2)->comment('限额最小值');
            $table->decimal('quota_max', 10, 2)->comment('限额最大值');
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
        Schema::dropIfExists('credit_line_products');
    }
}
