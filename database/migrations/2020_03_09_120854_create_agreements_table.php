<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgreementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agreements', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('admin_user_id');
            $table->foreign('admin_user_id')->references('id')->on('admin_users');
            $table->string('slug')->unique()->comment('slug');
            $table->string('type')->nullable()->comment('类型');
            $table->string('title')->nullable()->comment('标题');
            $table->longText('content')->nullable()->comment('内容');
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
        Schema::dropIfExists('agreements');
    }
}
