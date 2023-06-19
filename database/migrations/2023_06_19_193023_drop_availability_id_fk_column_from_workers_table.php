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
        Schema::table('workers', function (Blueprint $table) {
            $table->dropForeign(['availability_id']);
            $table->dropColumn('availability_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('workers', function (Blueprint $table) {
            $table->unsignedBigInteger('availability_id')->nullable();

            $table->foreign( 'availability_id' )
                  ->references( 'availability_id' )
                  ->on( 'worker_availabilities' )
                  ->onDelete( 'cascade' );
        });
    }
};
