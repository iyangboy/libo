<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserVipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_vips', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('vip_id');
            $table->foreign('vip_id')->references('id')->on('member_products')->onDelete('cascade');
            $table->string('no')->unique()->comment('编号');
            $table->decimal('total_amount', 10, 2)->comment('支付金额');
            $table->text('remark')->nullable()->comment('备注');
            $table->dateTime('paid_at')->nullable()->comment('支付时间');
            $table->string('payment_method')->nullable()->comment('支付方式');
            $table->string('payment_no')->nullable()->comment('支付平台订单号');
            $table->boolean('closed')->default(false)->comment('订单是否已关闭');
            $table->string('status')->default(\App\Models\UserVip::STATUS_PENDING)->comment('订单是否已关闭');
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
        Schema::dropIfExists('user_vips');
    }
}
