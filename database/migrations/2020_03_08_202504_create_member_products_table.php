<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMemberProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('slug')->comment('Slug');
            $table->string('title')->comment('标题');
            $table->text('description')->comment('描述');
            $table->string('image')->comment('简图');
            $table->boolean('on_sale')->default(true)->comment('是否上线');
            $table->unsignedInteger('sold_count')->default(0)->comment('销量');
            $table->decimal('price', 10, 2);
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
        Schema::dropIfExists('member_products');
    }
}
