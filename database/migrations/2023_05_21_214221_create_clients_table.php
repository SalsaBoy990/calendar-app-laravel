<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create( 'clients', function ( Blueprint $table ) {
            $table->id();
            $table->unsignedBigInteger( 'event_id' )->nullable();
            $table->string( 'name' )->unique();
            $table->string( 'address' );
            $table->integer( 'order' )->default( 0 );
            $table->timestamps();

            $table->foreign( 'event_id' )
                  ->references( 'event_id' )
                  ->on( 'events' )
                  ->onDelete( 'SET NULL' );
        } );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists( 'clients' );
    }
};
