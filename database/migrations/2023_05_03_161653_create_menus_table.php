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
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->unsignedBigInteger('shop_id');
            // FK 정의
            $table->foreign('shop_id')->references('id')->on('shops');

            $table->integer('price')->default(0);
            $table->enum('status', ['ready', 'sold_out', 'open'])->default('ready');
            $table->string('name', 64);
            $table->string('description', 256);
            $table->string('img_url')->nullable();

            // indexing
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menus');
    }
};
