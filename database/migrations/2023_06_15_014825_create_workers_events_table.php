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
        Schema::create('workers_events', function (Blueprint $table) {
            $table->unsignedBigInteger( 'worker_id' );
            $table->unsignedBigInteger( 'event_id' );

            $table->foreign( 'worker_id' )
                  ->references( 'id' )
                  ->on( 'workers' )
                  ->onDelete( 'cascade' );

            $table->foreign( 'event_id' )
                  ->references( 'event_id' )
                  ->on( 'events' )
                  ->onDelete( 'cascade' );

            $table->primary( [ 'worker_id', 'event_id' ] );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workers_events');
    }
};
