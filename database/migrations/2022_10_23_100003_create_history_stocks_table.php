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
        Schema::create('history_stoks', function (Blueprint $table) {
            $table->id();

            $table->integer('stok')->nullable();
            $table->foreignId('product_id')->nullable();
            $table->foreignId('pembelian_id')->nullable();
            $table->foreignId('penjualan_id')->nullable();
            $table->foreignId('user_id')->nullable();

            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('pembelian_id')->references('id')->on('pembelians')->onDelete('cascade');
            $table->foreign('penjualan_id')->references('id')->on('penjualans')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('history_stoks');
    }
};
