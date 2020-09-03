<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMarkersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('markers', function (Blueprint $table) {
            $table->id();
            $table->integer('plate_id');
            $table->string('type');
            $table->decimal('lat', 11, 8);
            $table->decimal('lng', 11, 8);
            $table->decimal('radius', 20, 6)->nullable();
            $table->integer('phone_number_prefix_id');
            $table->string('phone_number')->nullable();
            $table->string('email')->nullable();
            $table->text('additional_info')->nullable();
            $table->boolean('notify_when_found')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('markers');
    }
}
