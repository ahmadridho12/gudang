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
        Schema::create('permintaan', function (Blueprint $table) {
            $table->id('id_permintaan');
            $table->unsignedBigInteger('id_user'); // Contoh kolom pengguna yang membuat permintaan
            $table->unsignedBigInteger('no_trans'); // Mengacu ke tabel nomor_transaksi
            $table->text('keterangan'); // Kolom keterangan
            $table->unsignedBigInteger('bagian'); // Mengacu ke tabel bagian
            $table->date('tgl_permintaan'); // Kolom tanggal permintaan
            $table->integer('total'); // Kolom tanggal kadaluarsa
            $table->timestamps();

            $table->foreign('bagian')->references('id_bagian')->on('bagian')->onDelete('cascade');
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('no_trans')->references('id_notrans')->on('notrans')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('permintaan');
    }
};
