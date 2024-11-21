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
        Schema::create('notrans', function (Blueprint $table) {
            $table->id('id_notrans'); // Primary key
            $table->string('penamaan'); // Nama wilayah kejadian
            $table->integer('last_number')->default(0); // Nomor terakhir
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
        Schema::dropIfExists('notrans');
    }
};
