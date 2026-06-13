<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel 20: financial_transactions
        Schema::create('financial_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->nullable()->constrained('bookings')->nullOnDelete();
            $table->foreignId('payment_id')->nullable()->constrained('payments')->nullOnDelete();
            $table->enum('jenis_transaksi', ['pemasukan', 'pengeluaran']);
            $table->string('kategori'); // dp, pelunasan, biaya_pihak_lain
            $table->unsignedInteger('nominal');
            $table->text('keterangan')->nullable();
            $table->date('tanggal');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // Tabel 21: monthly_reports
        Schema::create('monthly_reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('bulan');
            $table->unsignedSmallInteger('tahun');
            $table->unsignedInteger('total_booking')->default(0);
            $table->unsignedInteger('total_dibayar')->default(0);
            $table->unsignedInteger('total_sisa_pelunasan')->default(0);
            $table->unsignedInteger('total_biaya_pihak_lain')->default(0);
            $table->unsignedInteger('total_gateway_fee')->default(0);
            $table->unsignedInteger('estimasi_bersih_owner')->default(0);
            $table->timestamps();

            $table->unique(['bulan', 'tahun']);
        });

        // Tabel 22: cancellation_requests
        Schema::create('cancellation_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();
            $table->text('alasan');
            $table->enum('status_persetujuan', ['diajukan', 'disetujui', 'ditolak'])->default('diajukan');
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // Tabel 23: reviews
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('package_id')->nullable()->constrained('service_packages')->nullOnDelete();
            $table->unsignedTinyInteger('rating');
            $table->text('komentar')->nullable();
            $table->string('foto')->nullable();
            $table->enum('status_review', ['tampil', 'disembunyikan'])->default('tampil');
            $table->timestamps();

            $table->unique('booking_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
        Schema::dropIfExists('cancellation_requests');
        Schema::dropIfExists('monthly_reports');
        Schema::dropIfExists('financial_transactions');
    }
};
