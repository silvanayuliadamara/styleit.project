<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('headline')->nullable();
            $table->text('description')->nullable();
            $table->string('icon')->default('bi-stars');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('service_packages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('service_categories')->cascadeOnDelete();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->unsignedInteger('price');
            $table->unsignedInteger('dp_amount');
            $table->unsignedInteger('quota_per_day')->default(1);
            $table->boolean('is_popular')->default(false);
            $table->string('image')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('package_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->constrained('service_packages')->cascadeOnDelete();
            $table->string('name');
            $table->unsignedInteger('quantity')->default(1);
            $table->string('unit')->nullable();
            $table->timestamps();
        });

        Schema::create('addons', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->unsignedInteger('price');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('blocked_dates', function (Blueprint $table) {
            $table->id();
            $table->date('blocked_date')->unique();
            $table->string('reason')->nullable();
            $table->timestamps();
        });

        Schema::create('portfolio_items', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('category');
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_code')->unique();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('package_id')->constrained('service_packages')->restrictOnDelete();
            $table->date('booking_date');
            $table->boolean('softlens')->default(false);
            $table->unsignedInteger('subtotal');
            $table->unsignedInteger('addon_total')->default(0);
            $table->unsignedInteger('total_price');
            $table->unsignedInteger('dp_amount');
            $table->unsignedInteger('remaining_payment');
            $table->enum('status', ['pending','menunggu_konfirmasi','diterima','ditolak','selesai','dibatalkan'])->default('pending');
            $table->enum('payment_status', ['belum_bayar','dp_diupload','dp_diterima','dp_ditolak','lunas'])->default('belum_bayar');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('booking_addons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->foreignId('addon_id')->constrained()->restrictOnDelete();
            $table->unsignedInteger('price');
            $table->timestamps();
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('amount');
            $table->string('proof_image')->nullable();
            $table->enum('status', ['pending','diterima','ditolak'])->default('pending');
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
        Schema::dropIfExists('booking_addons');
        Schema::dropIfExists('bookings');
        Schema::dropIfExists('portfolio_items');
        Schema::dropIfExists('blocked_dates');
        Schema::dropIfExists('addons');
        Schema::dropIfExists('package_items');
        Schema::dropIfExists('service_packages');
        Schema::dropIfExists('service_categories');
    }
};
