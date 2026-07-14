<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cancellation_requests', function (Blueprint $table) {
            $table->boolean('dp_dikembalikan')->default(false)->after('customer_dibaca');
            $table->integer('jumlah_dp_dikembalikan')->nullable()->after('dp_dikembalikan');
        });
    }

    public function down(): void
    {
        Schema::table('cancellation_requests', function (Blueprint $table) {
            $table->dropColumn(['dp_dikembalikan', 'jumlah_dp_dikembalikan']);
        });
    }
};
