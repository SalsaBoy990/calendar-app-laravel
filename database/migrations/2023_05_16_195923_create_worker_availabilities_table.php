<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create( 'worker_availabilities', function ( Blueprint $table ) {

            $table->id( 'availability_id' );
            $table->uuid( 'id' )->unique();

            $table->unsignedBigInteger( 'user_id' )->nullable();

            $table->string( 'start' );
            $table->string( 'end' );
            $table->string( 'description' )->nullable();
            $table->string( 'backgroundColor', 20 );


            $table->foreign( 'user_id' )
                  ->references( 'id' )
                  ->on( 'users' );
        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists( 'worker_availabilities' );
    }
};
