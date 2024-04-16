<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUnitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('kode_unit', 100);
            $table->integer('id_parent');
            $table->enum('status', ['empty', 'filled', 'late']);
            $table->enum('type', ['perumahan', 'kontrakan', 'kostan']);
            $table->enum('periode_pembayaran', ['year', 'month']);
            $table->string('nama_penghuni', 100)->nullable();
            $table->integer('no_identitas')->nullable();
            $table->text('alamat')->nullable();
            $table->string('provinsi')->nullable();
            $table->string('kota')->nullable();
            $table->integer('kode_pos')->nullable();
            $table->date('tanggal_mulai')->nullable();
            $table->timestamp('deleted_at')->nullable(); // Soft delete column
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
        Schema::dropIfExists('units');
    }
}
