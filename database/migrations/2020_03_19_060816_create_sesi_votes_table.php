<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSesiVotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sesi_votes', function (Blueprint $table) {
            $table->bigIncrements('id_sesi_vote');
            $table->string('ket_sesi');
            $table->timestamp('tgl_mulai_vote')->nullable();
            $table->timestamp('tgl_akhir_vote')->nullable();
            $table->boolean('status_sesi');
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
        Schema::dropIfExists('sesi_votes');
    }
}
