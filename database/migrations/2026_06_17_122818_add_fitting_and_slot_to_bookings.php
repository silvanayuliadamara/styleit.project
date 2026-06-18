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
        Schema::table('bookings', function (Blueprint $table) {
            if (! Schema::hasColumn('bookings', 'slot_waktu')) {
                $table->string('slot_waktu')->nullable()->after('tanggal_acara');
            }
            if (! Schema::hasColumn('bookings', 'tanggal_fitting')) {
                $table->date('tanggal_fitting')->nullable()->after('slot_waktu');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $columns = [];
            if (Schema::hasColumn('bookings', 'slot_waktu')) {
                $columns[] = 'slot_waktu';
            }
            if (Schema::hasColumn('bookings', 'tanggal_fitting')) {
                $columns[] = 'tanggal_fitting';
            }
            if (! empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
};
