<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBankCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_codes', function (Blueprint $table) {
            $table->Increments('id');
            $table->string('code')->comment('讯联机构号');
            $table->string('name')->comment('银行名称');
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
        Schema::dropIfExists('bank_codes');
    }
}
