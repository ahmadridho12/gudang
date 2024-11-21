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
        Schema::create('kategoribm', function (Blueprint $table) {
            $table->id('id_ibm');
            $table->string('nama_ibm');
            $table->decimal('ppn', 5, 2); // 5 digits total, 2 digits after the decimal
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
        Schema::dropIfExists('kategoribm');
    }
};
