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
        Schema::create('stok', function (Blueprint $table) {
            $table->id('id_stok'); // Primary key
            $table->unsignedBigInteger('id_barang'); // Foreign key ke tabel barangg
            $table->unsignedBigInteger('id_jenis');  // Foreign key ke tabel jenis
            $table->integer('harga'); // Kolom harga barang
            $table->integer('qty'); // Kolom quantity stok
            $table->timestamps();

            // Relasi ke tabel barangg
            $table->foreign('id_barang')->references('id_barang')->on('barangg')->onDelete('cascade');
            // Relasi ke tabel jenis
            $table->foreign('id_jenis')->references('id')->on('jenis')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stok');
    }
};
