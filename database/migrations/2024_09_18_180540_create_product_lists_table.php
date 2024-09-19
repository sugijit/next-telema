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
        Schema::create('product_lists', function (Blueprint $table) {
            $table->id();
            $table->string('list_name')->nullable();
            $table->string('list_alias')->nullable();
            $table->string('company_id')->nullable();
            $table->string('nl_link')->nullable();
            $table->text('fields')->nullable();
            $table->string('deleted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_lists');
    }
};
