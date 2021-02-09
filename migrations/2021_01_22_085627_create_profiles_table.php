<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // php artisan make:model Profile -m
        Schema::create('profiles', function (Blueprint $table) {
            //tipe data default big increments untuk primary key
            $table->bigIncrements('id');
            //unsigned artinya tidak boleh dibawah angka nol
            $table->bigInteger('user_id')->unsigned();
            //mendeklarasikan foreign key restrict
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('phone');
            $table->text('address');
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
        Schema::dropIfExists('profiles');
    }
}
