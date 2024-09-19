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
        Schema::table('product_lists', function (Blueprint $table) {
            $table->string('entry_link')->nullable()->after('nl_link');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_lists', function (Blueprint $table) {
            $table->dropColumn('entry_link');
        });
    }
};
