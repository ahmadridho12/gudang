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
        Schema::create('detailpermintaan', function (Blueprint $table) {
            $table->id('id_detailpermintaan');
            $table->unsignedBigInteger('id_permintaan');
            $table->unsignedBigInteger('id_barang');
            $table->integer('qty'); // Jumlah barang yang diminta
            $table->timestamps();

            $table->foreign('id_permintaan')->references('id_permintaan')->on('permintaan')->onDelete('cascade');
            $table->foreign('id_barang')->references('id_barang')->on('barangg')->onDelete('cascade');
        
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detailpermintaan');
    }
};
