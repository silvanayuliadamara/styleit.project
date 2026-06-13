<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel 24: business_profiles
        Schema::create('business_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('nama_brand');
            $table->string('subtitle')->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('alamat')->nullable();
            $table->string('kontak')->nullable();
            $table->string('jam_operasional')->nullable();
            $table->string('banner')->nullable();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // Tabel 25: portfolios (paralel dengan portfolio_items lama)
        Schema::create('portfolios', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('kategori');
            $table->string('foto')->nullable();
            $table->text('deskripsi')->nullable();
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        // Tabel 26: certifications
        Schema::create('certifications', function (Blueprint $table) {
            $table->id();
            $table->string('nama_certification');
            $table->string('lembaga')->nullable();
            $table->year('tahun')->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('foto_sertifikat')->nullable();
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // Tabel 27: whatsapp_settings
        Schema::create('whatsapp_settings', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_makeup_paket');
            $table->string('nomor_baju')->nullable();
            $table->text('template_makeup')->nullable();
            $table->text('template_baju')->nullable();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('whatsapp_settings');
        Schema::dropIfExists('certifications');
        Schema::dropIfExists('portfolios');
        Schema::dropIfExists('business_profiles');
    }
};
