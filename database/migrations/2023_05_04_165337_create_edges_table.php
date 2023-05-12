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
        Schema::create('edges', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('from_node_id');
            $table->unsignedBigInteger('to_node_id');

            $table->float('weight')->default(0);
            // FK 및 복합 Key 생성
            $table->foreign('from_node_id')->references('id')->on('nodes');
            $table->foreign('to_node_id')->references('id')->on('nodes');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('edges');
    }
};
