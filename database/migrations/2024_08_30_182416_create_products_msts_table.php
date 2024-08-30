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
        Schema::create('products_msts', function (Blueprint $table) {
            $table->id();
            $table->string('product_name')->nullable();
            $table->string('table_name')->nullable();
            $table->string('company_id')->nullable();
            $table->string('created_user_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products_msts');
    }
};
