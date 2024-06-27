<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bahans', function (Blueprint $table) {
            $table->id();
            $table->string('kode_bahan');
            $table->string('nama_bahan');
            $table->string('sat');
            $table->integer('harga_master');
            $table->unsignedBigInteger('kategori_id');
            $table->unsignedBigInteger('user_id_input');
            $table->timestamp('tgl_input');
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('user_id_revisi')->nullable();
            $table->timestamp('tgl_revisi')->nullable();
            $table->integer('harga_terbaru')->nullable();
            $table->timestamp('tgl_harga_baru')->nullable();

            //relationship kategori
            $table->foreign('kategori_id')->references('id')->on('categories');

            //relationship user input
            $table->foreign('user_id_input')->references('id')->on('users');

            //relationship user update
            $table->foreign('user_id_revisi')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bahans');
    }
};
