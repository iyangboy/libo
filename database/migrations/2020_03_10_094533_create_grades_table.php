<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGradesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grades', function (Blueprint $table) {
            $table->Increments('id');
            $table->string('slug')->nullable()->comment('Slug');
            $table->string('name')->nullable()->comment('名称');
            $table->text('content')->nullable()->comment('内容');
            $table->decimal('loan_limit', 10, 2)->comment('贷款限额');
            $table->decimal('interest_rate', 10, 4)->comment('日利率');
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
        Schema::dropIfExists('grades');
    }
}
