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
        Schema::table('worker_availabilities', function (Blueprint $table) {
            $table->renameColumn('availability_id', 'id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('worker_availabilities', function (Blueprint $table) {
            $table->renameColumn('id', 'availability_id');
        });
    }
};
