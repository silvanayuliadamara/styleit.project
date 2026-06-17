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
        Schema::table('cancellation_requests', function (Blueprint $table) {
            // Track whether the customer has seen/acknowledged the approval or rejection
            $table->boolean('customer_dibaca')->default(false)->after('approved_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cancellation_requests', function (Blueprint $table) {
            $table->dropColumn('customer_dibaca');
        });
    }
};