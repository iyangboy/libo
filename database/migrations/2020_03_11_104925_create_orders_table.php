<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->comment('下单的用户 ID');
            $table->unsignedBigInteger('product_id')->comment('商品 ID');
            $table->string('no')->unique()->comment('订单流水号');
            $table->text('address')->comment('JSON 格式的收货地址');
            $table->decimal('loan_amount', 10, 2)->comment('贷款金额');
            $table->decimal('price', 10, 2)->comment('服务费');
            $table->unsignedBigInteger('product_by_stage_id')->comment('商品 ID');
            $table->Integer('by_stage')->default(1)->comment('分期数');
            $table->text('remark')->nullable()->comment('订单备注');
            $table->dateTime('paid_at')->nullable()->comment('支付时间');
            $table->string('payment_method')->nullable()->comment('支付方式');
            $table->string('payment_no')->nullable()->comment('支付平台订单号');
            $table->string('refund_status')->default(\App\Models\Order::REFUND_STATUS_PENDING)->comment('退款状态');
            $table->string('refund_no')->unique()->nullable()->comment('退款单号');
            $table->boolean('closed')->default(false)->comment('订单是否已关闭');
            $table->boolean('reviewed')->default(false)->comment('订单是否已评价');
            $table->string('ship_status')->default(\App\Models\Order::SHIP_STATUS_PENDING)->comment('订单状态');
            $table->text('ship_data')->nullable()->comment('JSON订单数据');
            $table->text('extra')->nullable()->comment('其他额外的数据');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('product_by_stage_id')->references('id')->on('product_by_stages');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
