<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePinjamansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pinjaman', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nama_pinjaman');
            $table->integer('anggota_id')->index('anggota_id_foreign');
            $table->string('besar_pinjaman');
            $table->string('tgl_pengajuan_pinjaman');
            $table->string('tgl_acc_pinjaman');
            $table->string('tgl_pinjaman');
            $table->string('tgl_pelunasan');
            $table->integer('angsuran_id')->index('angsuran_id_foreign');
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
        Schema::dropIfExists('pinjaman');
    }
}
