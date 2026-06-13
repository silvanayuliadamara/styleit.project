<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel 10: carts
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->enum('status_cart', ['aktif', 'checkout', 'expired'])->default('aktif');
            $table->timestamps();
        });

        // Tabel 11: cart_items
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cart_id')->constrained('carts')->cascadeOnDelete();
            $table->foreignId('package_id')->constrained('service_packages')->restrictOnDelete();
            $table->foreignId('schedule_id')->constrained('schedules')->restrictOnDelete();
            $table->boolean('softlens')->default(false);
            $table->text('catatan')->nullable();
            $table->unsignedInteger('harga_paket');
            $table->unsignedInteger('subtotal')->default(0);
            $table->timestamps();
        });

        // Tabel 12: cart_item_addons
        Schema::create('cart_item_addons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cart_item_id')->constrained('cart_items')->cascadeOnDelete();
            $table->foreignId('addon_id')->constrained('addons')->restrictOnDelete();
            $table->foreignId('addon_option_id')->nullable()->constrained('addon_options')->nullOnDelete();
            $table->unsignedInteger('qty')->default(1);
            $table->unsignedInteger('harga');
            $table->unsignedInteger('subtotal');
            $table->timestamps();
        });

        // Tabel 13: checkouts
        Schema::create('checkouts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cart_id')->constrained('carts')->restrictOnDelete();
            $table->foreignId('user_id')->constrained('users')->restrictOnDelete();
            $table->string('nama_customer');
            $table->string('no_hp');
            $table->text('alamat_customer')->nullable();
            $table->string('instagram_customer')->nullable();
            $table->text('catatan_tambahan')->nullable();
            $table->unsignedInteger('total_harga');
            $table->unsignedInteger('nominal_dp');
            $table->unsignedInteger('sisa_pelunasan');
            $table->enum('status_checkout', [
                'menunggu_dp', 'dp_diupload', 'dp_diterima',
                'dp_ditolak', 'lunas', 'dibatalkan'
            ])->default('menunggu_dp');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('checkouts');
        Schema::dropIfExists('cart_item_addons');
        Schema::dropIfExists('cart_items');
        Schema::dropIfExists('carts');
    }
};
