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
        Schema::create('detail_masuk', function (Blueprint $table) {
            $table->id('id_detailmasuk');
            $table->unsignedBigInteger('barang_masuk_id');
            $table->unsignedBigInteger('id_barang');
            $table->integer('jumlah');
            $table->decimal('harga_sebelum_ppn', 10, 2);
            $table->unsignedBigInteger('kategori_ppn_id');
            $table->decimal('harga_setelah_ppn', 10, 2);
            $table->decimal('total_setelah_ppn', 10, 2);
            $table->timestamps();

            $table->foreign('barang_masuk_id')->references('id_masuk')->on('barang_masuk')->onDelete('cascade');
            $table->foreign('id_barang')->references('id_barang')->on('barangg')->onDelete('cascade');
            $table->foreign('kategori_ppn_id')->references('id_kategoribm')->on('kategoribm')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detail_masuk');
    }
};
