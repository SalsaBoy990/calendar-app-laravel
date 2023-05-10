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
        Schema::create('events', function (Blueprint $table) {
            $table->id('event_id');
            $table->uuid('id')->unique();

            $table->string('title');
            $table->string('start');
            $table->string('end')
                  ->nullable();

            $table->string( 'address' )
                  ->nullable();

            $table->string( 'description' )
                  ->nullable();

            $table->enum( 'status', [ 'pending', 'opened', 'completed', 'closed' ] )
                  ->default( 'opened' );


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('events');
    }
};
