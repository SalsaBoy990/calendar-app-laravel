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
        Schema::create('workers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('availability_id')->nullable();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone');

            $table->timestamps();

            $table->foreign( 'availability_id' )
                  ->references( 'availability_id' )
                  ->on( 'worker_availabilities' )
                  ->onDelete( 'cascade' );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workers');
    }
};
