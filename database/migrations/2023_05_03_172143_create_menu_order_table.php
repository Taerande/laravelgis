<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu_order', function (Blueprint $table) {
        $table->unsignedBigInteger('order_id');
        $table->unsignedBigInteger('menu_id');

        // FK 정의
        $table->foreign('order_id')->references('id')->on('orders');
        $table->foreign('menu_id')->references('id')->on('menus');
        $table->unsignedTinyInteger('amount')->default(1);

        $table->primary(['order_id', 'menu_id']);
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
        Schema::dropIfExists('menu_order');
    }
};
