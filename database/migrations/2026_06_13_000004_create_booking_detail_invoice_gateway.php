<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel 15: booking_details (snapshot paket)
        Schema::create('booking_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();
            $table->foreignId('package_id')->nullable()->constrained('service_packages')->nullOnDelete();
            $table->string('nama_paket');
            $table->string('kategori');
            $table->unsignedInteger('harga_paket');
            $table->boolean('softlens')->default(false);
            $table->text('catatan')->nullable();
            $table->timestamps();
        });

        // Tabel 18: payment_gateway_transactions
        Schema::create('payment_gateway_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_id')->constrained('payments')->cascadeOnDelete();
            $table->string('gateway_name')->default('midtrans');
            $table->string('gateway_order_id')->unique();
            $table->string('gateway_transaction_id')->nullable();
            $table->string('payment_type')->nullable();
            $table->string('bank')->nullable();
            $table->string('va_number')->nullable();
            $table->unsignedInteger('gross_amount');
            $table->string('transaction_status')->nullable();
            $table->string('fraud_status')->nullable();
            $table->json('callback_payload')->nullable();
            $table->timestamp('expired_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });

        // Tabel 19: invoices
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();
            $table->string('nomor_invoice')->unique();
            $table->unsignedInteger('total_harga');
            $table->unsignedInteger('dp_dibayar')->default(0);
            $table->unsignedInteger('total_dibayar')->default(0);
            $table->unsignedInteger('sisa_pelunasan')->default(0);
            $table->enum('status_invoice', [
                'menunggu_dp', 'dp_dibayar', 'lunas', 'dibatalkan',
            ])->default('menunggu_dp');
            $table->date('tanggal_invoice');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('payment_gateway_transactions');
        Schema::dropIfExists('booking_details');
    }
};
