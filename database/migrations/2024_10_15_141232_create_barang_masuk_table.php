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
        Schema::create('barang_masuk', function (Blueprint $table) {
            $table->id('id_masuk');
            $table->unsignedBigInteger('suplier_id');
            $table->string('no_transaksi')->unique();
            $table->date('tgl_masuk');
            $table->string('Keterangan');
            $table->timestamps();

            $table->foreign('suplier_id')->references('id_suplier')->on('suplierr')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('barang_masuk');
    }
};
