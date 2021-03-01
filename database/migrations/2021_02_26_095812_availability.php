<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Availability extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('availability', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('provider_id')->unsigned();
            $table->foreign('provider_id')
                ->references('id')
                ->on('provider')
                ->constrained();
            $table->bigInteger('patient_id')->unsigned()->nullable();
            $table->foreign('patient_id')
                ->references('id')
                ->on('patient');
            $table->timestamp('slot_start')->nullable();
            $table->timestamp('slot_end')->nullable();
            $table->timestamps();
            $table->unique(['provider_id', 'slot_start', 'slot_end']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('availability');
    }
}
