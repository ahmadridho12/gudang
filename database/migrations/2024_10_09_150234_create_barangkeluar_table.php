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
        Schema::create('barang_keluar', function (Blueprint $table) {
            $table->id('id_barangkeluar');
            $table->unsignedBigInteger('id_permintaan');
            $table->unsignedBigInteger('id_barang');
            $table->integer('qty'); // Jumlah barang yang keluar
            $table->unsignedBigInteger('id_jenis'); // Jumlah barang yang keluar
            $table->timestamps();
        
            // Foreign key ke tabel permintaan
            $table->foreign('id_permintaan')
                  ->references('id_permintaan')
                  ->on('permintaan')
                  ->onDelete('cascade');
        
            // Foreign key ke tabel barang
            $table->foreign('id_barang')
                  ->references('id_barang')
                  ->on('barangg')
                  ->onDelete('cascade');
            // Foreign key ke tabel jenis
            $table->foreign('id_jenis')
                  ->references('id')
                  ->on('jenis')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('barangkeluar');
    }
};
