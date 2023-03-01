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
        Schema::create('markers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('plate_id');
            $table->string('type');
            $table->decimal('lat', 11, 8);
            $table->decimal('lng', 11, 8);
            $table->decimal('radius', 20, 6)->nullable();
            $table->string('phone_number')->nullable();
            $table->string('email')->nullable();
            $table->text('additional_info')->nullable();
            $table->boolean('notify_when_found')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('markers');
    }
};
