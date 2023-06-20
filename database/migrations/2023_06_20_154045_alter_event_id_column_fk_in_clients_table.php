<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::table( 'clients', function ( Blueprint $table ) {
            $table->dropForeign( [ 'event_id' ] );
            $table->dropColumn('event_id');
        } );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::table( 'clients', function ( Blueprint $table ) {
            $table->unsignedBigInteger( 'event_id' )->nullable();
            $table->foreign( 'event_id' )
                  ->references( 'event_id' )
                  ->on( 'events' )
                  ->onDelete( 'SET NULL' );
        } );
    }
};
