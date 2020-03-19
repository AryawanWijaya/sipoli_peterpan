<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('votes', function (Blueprint $table) {
            $table->bigIncrements('id_vote');
            $table->bigInteger('id_sesi_vote')->references('id_sesi_vote')->on('sesi_vote');
            $table->timestamp('tgl_vote');
            $table->bigInteger('id_org_di_vote')->references('id')->on('users');
            $table->bigInteger('id_org_yg_vote')->references('id')->on('users')->nullable();
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
        Schema::dropIfExists('votes');
    }
}
