<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('destinations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('category')->default('wisata-alam');
            $table->text('description');
            $table->text('address')->nullable();
            $table->string('contact')->nullable();
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->string('main_image');
            $table->json('gallery')->nullable();
            $table->json('entry_fees')->nullable();
            $table->string('operating_hours')->default('24 jam');
            $table->text('visitor_info')->nullable();
            $table->json('activities')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            // Indexes for better performance
            $table->index('category');
            $table->index('is_active');
            $table->index(['latitude', 'longitude']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('destinations');
    }
};