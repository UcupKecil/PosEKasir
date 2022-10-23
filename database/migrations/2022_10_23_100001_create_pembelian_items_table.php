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
        Schema::create('pembelian_items', function (Blueprint $table) {
            $table->id();
            $table->decimal('price', 8, 4);
            $table->integer('quantity')->default(1);
            $table->foreignId('pembelian_id');
            $table->foreignId('product_id');
            $table->timestamps();

            $table->foreign('pembelian_id')->references('id')->on('pembelians')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_items');
    }
};
