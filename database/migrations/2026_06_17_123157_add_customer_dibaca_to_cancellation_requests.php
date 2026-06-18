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
            if (! Schema::hasColumn('cancellation_requests', 'customer_dibaca')) {
                $table->boolean('customer_dibaca')->default(false)->after('approved_by');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cancellation_requests', function (Blueprint $table) {
            if (Schema::hasColumn('cancellation_requests', 'customer_dibaca')) {
                $table->dropColumn('customer_dibaca');
            }
        });
    }
};
