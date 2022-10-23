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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('image');
            $table->string('barcode')->unique();
            $table->decimal('harga_beli', 14, 2);
            $table->decimal('price', 14, 2);
            $table->foreignId('kategori_id');
            $table->boolean('status')->default('1');
            $table->timestamps();
            // $table->integer('quantity');

            $table->foreign('kategori_id')->references('id')->on('kategoris')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
};
