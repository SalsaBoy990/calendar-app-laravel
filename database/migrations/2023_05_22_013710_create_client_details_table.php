<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create( 'client_details', function ( Blueprint $table ) {
            $table->id();
            $table->unsignedBigInteger( 'client_id' )->nullable();
            $table->string( 'contact_person' )->nullable();
            $table->string( 'phone_number' )->nullable();
            $table->string( 'email' )->nullable();
            $table->string( 'tax_number' )->nullable();
            $table->timestamps();

            $table->foreign( 'client_id' )
                  ->references( 'id' )
                  ->on( 'clients' )
                  ->onDelete( 'cascade' );
        } );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists( 'client_details' );
    }
};
