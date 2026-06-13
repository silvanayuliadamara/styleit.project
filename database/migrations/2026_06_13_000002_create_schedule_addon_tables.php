<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel 6: schedules
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('service_categories')->cascadeOnDelete();
            $table->date('tanggal');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->enum('jenis_jadwal', ['pagi', 'siang', 'sore', 'layanan_lain'])->default('pagi');
            $table->unsignedInteger('kuota')->default(1);
            $table->unsignedInteger('terpakai')->default(0);
            $table->enum('status', ['tersedia', 'penuh', 'diblokir'])->default('tersedia');
            $table->string('catatan')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['category_id', 'tanggal', 'jenis_jadwal']);
        });

        // Tabel 8: category_addons
        Schema::create('category_addons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('service_categories')->cascadeOnDelete();
            $table->foreignId('addon_id')->constrained('addons')->cascadeOnDelete();
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();

            $table->unique(['category_id', 'addon_id']);
        });

        // Tabel 9: addon_options
        Schema::create('addon_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('addon_id')->constrained('addons')->cascadeOnDelete();
            $table->string('nama_option');
            $table->string('tipe_option')->nullable();
            $table->unsignedInteger('harga');
            $table->boolean('is_pihak_lain')->default(false);
            $table->unsignedInteger('biaya_pihak_lain')->default(0);
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('addon_options');
        Schema::dropIfExists('category_addons');
        Schema::dropIfExists('schedules');
    }
};
