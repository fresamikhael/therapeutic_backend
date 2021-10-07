<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDoctorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('doctors', function (Blueprint $table) {
            $table->id();

            $table->integer('users_d');
            $table->string('name');
            $table->string('phone_number');
            $table->string('experience');
            $table->string('lisence_number');
            $table->string('language');
            $table->string('photo')->nullable();
            $table->float('price');
            $table->bigInteger('categories_id');
            $table->bigInteger('hospitals_id');
            $table->string('categories_slug');
            $table->string('hospitals_slug');

            $table->softDeletes();
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
        Schema::dropIfExists('doctors');
    }
}
