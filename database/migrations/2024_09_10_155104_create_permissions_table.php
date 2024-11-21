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
        Schema::create('permissions', function (Blueprint $table) {
            $table->id('id_permission');
            $table->string('nama');
            $table->string('nik');
            $table->string('golongan');
            $table->string('unit_kerja');
            $table->date('tgl_buat');
            $table->date('tgl_mulai');
            $table->date('tgl_selesai');
            $table->text('perihal');
            $table->string('foto');
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
        Schema::dropIfExists('permissions');
    }
};
