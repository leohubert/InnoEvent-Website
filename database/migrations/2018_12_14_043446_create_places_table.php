<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlacesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::enableForeignKeyConstraints();

        Schema::create('places', function (Blueprint $table) {
            $table->uuid('id');
            $table->primary('id');
            $table->string('place_id');
            $table->string('color');
            $table->double('price');
            $table->uuid('event_id');
            $table->foreign('event_id')->references('id')->on('events');
            $table->timestamps();
        });

        Schema::create('place_offers', function (Blueprint $table) {
            $table->string('place_id');
            $table->string('offer_id');
            $table->timestamps();
        });

        Schema::disableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('places');
    }
}
