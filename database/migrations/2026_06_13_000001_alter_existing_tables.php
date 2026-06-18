<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // -------------------------------------------------------
        // roles — tambah nama_role (Spatie sudah punya id, name, guard_name)
        // -------------------------------------------------------
        Schema::table('roles', function (Blueprint $table) {
            if (! Schema::hasColumn('roles', 'nama_role')) {
                $table->string('nama_role')->nullable()->after('name');
            }
        });

        // -------------------------------------------------------
        // users — tambah role_id FK (enum role lama dibiarkan)
        // -------------------------------------------------------
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'role_id')) {
                $table->unsignedBigInteger('role_id')->nullable()->after('id');
                $table->foreign('role_id')->references('id')->on('roles')->nullOnDelete();
            }
        });

        // -------------------------------------------------------
        // addons — tambah harga_default, is_pihak_lain, biaya_pihak_lain, status
        // -------------------------------------------------------
        Schema::table('addons', function (Blueprint $table) {
            if (! Schema::hasColumn('addons', 'harga_default')) {
                $table->unsignedInteger('harga_default')->default(0)->after('description');
            }
            if (! Schema::hasColumn('addons', 'is_pihak_lain')) {
                $table->boolean('is_pihak_lain')->default(false)->after('harga_default');
            }
            if (! Schema::hasColumn('addons', 'biaya_pihak_lain')) {
                $table->unsignedInteger('biaya_pihak_lain')->default(0)->after('is_pihak_lain');
            }
            if (! Schema::hasColumn('addons', 'status')) {
                $table->enum('status', ['aktif', 'nonaktif'])->default('aktif')->after('biaya_pihak_lain');
            }
        });

        // -------------------------------------------------------
        // service_packages — tambah butuh_makeup, butuh_baju,
        //   softlens_wajib_pilih, status
        // -------------------------------------------------------
        Schema::table('service_packages', function (Blueprint $table) {
            if (! Schema::hasColumn('service_packages', 'butuh_makeup')) {
                $table->boolean('butuh_makeup')->default(true)->after('is_popular');
            }
            if (! Schema::hasColumn('service_packages', 'butuh_baju')) {
                $table->boolean('butuh_baju')->default(false)->after('butuh_makeup');
            }
            if (! Schema::hasColumn('service_packages', 'softlens_wajib_pilih')) {
                $table->boolean('softlens_wajib_pilih')->default(false)->after('butuh_baju');
            }
            if (! Schema::hasColumn('service_packages', 'status')) {
                $table->enum('status', ['aktif', 'nonaktif'])->default('aktif')->after('softlens_wajib_pilih');
            }
        });

        // -------------------------------------------------------
        // package_items — tambah is_pihak_lain, biaya_pihak_lain, keterangan
        // -------------------------------------------------------
        Schema::table('package_items', function (Blueprint $table) {
            if (! Schema::hasColumn('package_items', 'is_pihak_lain')) {
                $table->boolean('is_pihak_lain')->default(false)->after('unit');
            }
            if (! Schema::hasColumn('package_items', 'biaya_pihak_lain')) {
                $table->unsignedInteger('biaya_pihak_lain')->default(0)->after('is_pihak_lain');
            }
            if (! Schema::hasColumn('package_items', 'keterangan')) {
                $table->string('keterangan')->nullable()->after('biaya_pihak_lain');
            }
        });

        // -------------------------------------------------------
        // bookings — tambah checkout_id, schedule_id, tanggal_acara,
        //   total_dibayar, sisa_pelunasan, status_layanan
        // -------------------------------------------------------
        Schema::table('bookings', function (Blueprint $table) {
            if (! Schema::hasColumn('bookings', 'checkout_id')) {
                $table->unsignedBigInteger('checkout_id')->nullable()->after('user_id');
            }
            if (! Schema::hasColumn('bookings', 'schedule_id')) {
                $table->unsignedBigInteger('schedule_id')->nullable()->after('checkout_id');
            }
            if (! Schema::hasColumn('bookings', 'tanggal_acara')) {
                $table->date('tanggal_acara')->nullable()->after('booking_date');
            }
            if (! Schema::hasColumn('bookings', 'total_dibayar')) {
                $table->unsignedInteger('total_dibayar')->default(0)->after('dp_amount');
            }
            if (! Schema::hasColumn('bookings', 'sisa_pelunasan')) {
                $table->unsignedInteger('sisa_pelunasan')->default(0)->after('total_dibayar');
            }
            if (! Schema::hasColumn('bookings', 'status_layanan')) {
                $table->enum('status_layanan', [
                    'pending', 'terjadwal', 'selesai', 'dibatalkan',
                ])->default('pending')->after('status');
            }
        });

        // -------------------------------------------------------
        // booking_addons — tambah snapshot: addon_option_id, nama_addon,
        //   nama_option, qty, subtotal, is_pihak_lain, biaya_pihak_lain
        // -------------------------------------------------------
        Schema::table('booking_addons', function (Blueprint $table) {
            if (! Schema::hasColumn('booking_addons', 'addon_option_id')) {
                $table->unsignedBigInteger('addon_option_id')->nullable()->after('addon_id');
            }
            if (! Schema::hasColumn('booking_addons', 'nama_addon')) {
                $table->string('nama_addon')->nullable()->after('addon_option_id');
            }
            if (! Schema::hasColumn('booking_addons', 'nama_option')) {
                $table->string('nama_option')->nullable()->after('nama_addon');
            }
            if (! Schema::hasColumn('booking_addons', 'qty')) {
                $table->unsignedInteger('qty')->default(1)->after('nama_option');
            }
            if (! Schema::hasColumn('booking_addons', 'subtotal')) {
                $table->unsignedInteger('subtotal')->default(0)->after('price');
            }
            if (! Schema::hasColumn('booking_addons', 'is_pihak_lain')) {
                $table->boolean('is_pihak_lain')->default(false)->after('subtotal');
            }
            if (! Schema::hasColumn('booking_addons', 'biaya_pihak_lain')) {
                $table->unsignedInteger('biaya_pihak_lain')->default(0)->after('is_pihak_lain');
            }
        });

        // -------------------------------------------------------
        // payments — tambah checkout_id, user_id, tipe_pembayaran,
        //   metode, nominal
        // -------------------------------------------------------
        Schema::table('payments', function (Blueprint $table) {
            if (! Schema::hasColumn('payments', 'checkout_id')) {
                $table->unsignedBigInteger('checkout_id')->nullable()->after('booking_id');
            }
            if (! Schema::hasColumn('payments', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable()->after('checkout_id');
            }
            if (! Schema::hasColumn('payments', 'tipe_pembayaran')) {
                $table->enum('tipe_pembayaran', ['dp', 'pelunasan', 'full'])->default('dp')->after('user_id');
            }
            if (! Schema::hasColumn('payments', 'metode')) {
                $table->string('metode')->nullable()->after('tipe_pembayaran');
            }
            if (! Schema::hasColumn('payments', 'nominal')) {
                $table->unsignedInteger('nominal')->default(0)->after('metode');
            }
        });
    }

    public function down(): void
    {
        Schema::table('roles', fn ($t) => $t->dropColumn(['nama_role']));
        Schema::table('users', function ($t) {
            $t->dropForeign(['role_id']);
            $t->dropColumn(['role_id']);
        });
        Schema::table('addons', fn ($t) => $t->dropColumn(['harga_default', 'is_pihak_lain', 'biaya_pihak_lain', 'status']));
        Schema::table('service_packages', fn ($t) => $t->dropColumn(['butuh_makeup', 'butuh_baju', 'softlens_wajib_pilih', 'status']));
        Schema::table('package_items', fn ($t) => $t->dropColumn(['is_pihak_lain', 'biaya_pihak_lain', 'keterangan']));
        Schema::table('bookings', fn ($t) => $t->dropColumn(['checkout_id', 'schedule_id', 'tanggal_acara', 'total_dibayar', 'sisa_pelunasan', 'status_layanan']));
        Schema::table('booking_addons', fn ($t) => $t->dropColumn(['addon_option_id', 'nama_addon', 'nama_option', 'qty', 'subtotal', 'is_pihak_lain', 'biaya_pihak_lain']));
        Schema::table('payments', fn ($t) => $t->dropColumn(['checkout_id', 'user_id', 'tipe_pembayaran', 'metode', 'nominal']));
    }
};
