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
        Schema::table( 'users', function ( Blueprint $table ) {
            $table->unsignedBigInteger( 'availability_id' )->nullable()->after( 'role_id' );

            $table->foreign( 'availability_id' )
                  ->references( 'availability_id' )
                  ->on( 'worker_availabilities' )
                  ->onDelete( 'cascade' );
        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table( 'users', function ( Blueprint $table ) {
            $table->dropForeign( [ 'availability_id' ] );
            $table->dropColumn( 'availability_id' );
        } );
    }
};
