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
        Schema::create('cuti', function (Blueprint $table) {
            $table->id('id_cuti');
            $table->string('nama');
            $table->integer('nik');
            $table->string('nama_jabatan');
            $table->string('golongan');
            $table->integer('no_hp');
            $table->date('mulai_cuti');
            $table->date('sampai_cuti');
            $table->string('kantor');
            $table->date('tgl_buat');
            $table->string('nama_kuasa');
            $table->integer('nik_kuasa');
            $table->string('jabatan_kuasa');
            $table->integer('nohp_kuasa');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cuti');
    }
};
