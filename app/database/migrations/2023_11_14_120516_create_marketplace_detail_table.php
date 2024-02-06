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
        Schema::create('marketplace_detail', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('site_id');
            $table->foreign('site_id')->references('id')->on('marketplaces'); // Assuming 'sites' is the related table

            $table->string('location');
            $table->string('shipping_service');
            $table->decimal('shipping_service_cost', 10, 2); // Adjust precision and scale based on your requirements
            $table->boolean('free_shipping');
            $table->string('shipping_type');
            $table->integer('dispatch_time_max');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marketplace_detail');
    }
};
