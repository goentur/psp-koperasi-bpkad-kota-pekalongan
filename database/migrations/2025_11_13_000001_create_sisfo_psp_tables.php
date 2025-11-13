<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateSisfoPspTables extends Migration
{
     /**
      * Run the migrations.
      *
      * @return void
      */
     public function up()
     {
          // 1. Tabel Master: s_anggota
          Schema::create('new_s_anggota', function (Blueprint $table) {
               $table->id();
               $table->string('nik')->unique(); // id(2 char)
               $table->string('nama');
               $table->text('alamat')->nullable();
               $table->string('bidang')->nullable();
               $table->string('no_telp')->nullable();
               $table->string('status_kepegawaian')->nullable();
               $table->date('tgl_daftar')->nullable();
               $table->uuid('created_by')->nullable();
               $table->uuid('updated_by')->nullable();
               $table->uuid('deleted_by')->nullable();
               $table->timestamps();
               $table->softDeletes();
          });

          // 2. Tabel Master: s_akun
          Schema::create('new_s_akun', function (Blueprint $table) {
               $table->id();
               $table->string('kode', 3)->primary();
               $table->string('nama');
               $table->string('jenis');
               $table->text('keterangan')->nullable();
               $table->uuid('created_by')->nullable();
               $table->uuid('updated_by')->nullable();
               $table->uuid('deleted_by')->nullable();
               $table->timestamps();
               $table->softDeletes();
          });

          // 3. Tabel Master: s_prod_simpanan
          Schema::create('new_s_prod_simpanan', function (Blueprint $table) {
               $table->id();
               $table->string('kode', 2)->unique(); // id(2 char)
               $table->string('nama');
               $table->bigInteger('admin')->nullable();
               $table->decimal('bunga', 5, 2)->default(0); // bunga
               $table->text('keterangan')->nullable();
               $table->uuid('created_by')->nullable();
               $table->uuid('updated_by')->nullable();
               $table->uuid('deleted_by')->nullable();
               $table->timestamps();
               $table->softDeletes();
          });

          // 4. Tabel Master: s_prod_pinjaman
          Schema::create('new_s_prod_pinjaman', function (Blueprint $table) {
               $table->id();
               $table->string('kode', 2)->unique(); // id(2 char)
               $table->string('nama');
               $table->bigInteger('admin')->nullable();
               $table->decimal('bunga', 5, 2)->default(0); // bunga
               $table->text('keterangan')->nullable();
               $table->uuid('created_by')->nullable();
               $table->uuid('updated_by')->nullable();
               $table->uuid('deleted_by')->nullable();
               $table->timestamps();
               $table->softDeletes();
          });

          // 5. Tabel Master: s_jns_trans_simpanan
          Schema::create('new_s_jns_trans', function (Blueprint $table) {
               $table->string('kode_trans', 10)->primary(); // kode_trans(2 char)
               $table->enum('module_source', ['simpanan', 'pinjaman', 'umum']);
               $table->string('nama');
               $table->enum('mutasi', ['debit', 'kredit']);
               $table->text('keterangan')->nullable();
               $table->uuid('created_by')->nullable();
               $table->uuid('updated_by')->nullable();
               $table->uuid('deleted_by')->nullable();
               $table->timestamps();
               $table->softDeletes();
          });

          // 8. Tabel Transaksi: t_simpanan
          Schema::create('new_t_simpanan', function (Blueprint $table) {
               $table->id();
               $table->bigInteger('anggota_id');
               $table->bigInteger('s_prod_simpanan_id');
               $table->string('no_rekening', 8)->unique(); // no_rekening(8char)
               $table->date('tgl_daftar')->nullable();
               $table->uuid('created_by')->nullable();
               $table->uuid('updated_by')->nullable();
               $table->uuid('deleted_by')->nullable();
               $table->timestamps();
               $table->softDeletes();
          });

          // 9. Tabel Transaksi: t_pinjaman
          Schema::create('new_t_pinjaman', function (Blueprint $table) {
               $table->id();
               $table->bigInteger('anggota_id');
               $table->bigInteger('prod_pinjaman_id');
               $table->string('no_rekening', 8)->unique(); // no_rekening(8char)
               $table->date('tgl_daftar')->nullable();
               $table->bigInteger('plafon')->default(0);
               $table->integer('jangka_waktu')->default(0);
               $table->uuid('created_by')->nullable();
               $table->uuid('updated_by')->nullable();
               $table->uuid('deleted_by')->nullable();
               $table->timestamps();
               $table->softDeletes();
          });

          // 10. Tabel Transaksi: t_trans_simpanan
          Schema::create('new_t_trans_simpanan', function (Blueprint $table) {
               $table->id();
               $table->bigInteger('simpanan_id');
               $table->string('kode_trans', 10)->comment('ambil dari tabel s_jns_trans');
               $table->date('tgl_trans');
               $table->string('jenis_trans', 2);
               $table->bigInteger('nominal')->default(0);
               $table->text('keterangan')->nullable();
               $table->uuid('created_by')->nullable();
               $table->uuid('updated_by')->nullable();
               $table->uuid('deleted_by')->nullable();
               $table->timestamps();
               $table->softDeletes();
          });

          // 11. Tabel Transaksi: t_trans_pinjaman
          Schema::create('new_t_trans_pinjaman', function (Blueprint $table) {
               $table->id();
               $table->bigInteger('pinjaman_id');
               $table->string('kode_trans', 10)->comment('ambil dari tabel s_jns_trans');
               $table->date('tgl_trans');
               $table->string('jenis_trans', 2);
               $table->bigInteger('nominal')->default(0);
               $table->text('keterangan')->nullable();
               $table->uuid('created_by')->nullable();
               $table->uuid('updated_by')->nullable();
               $table->uuid('deleted_by')->nullable();
               $table->timestamps();
               $table->softDeletes();
          });

          // 12. Tabel Transaksi: t_trans_umum
          Schema::create('new_t_trans_umum', function (Blueprint $table) {
               $table->id();
               $table->string('kode_trans', 10)->comment('ambil dari tabel s_jns_trans');
               $table->date('tgl_trans');
               $table->bigInteger('nominal')->default(0);
               $table->text('keterangan')->nullable();
               $table->uuid('created_by')->nullable();
               $table->uuid('updated_by')->nullable();
               $table->uuid('deleted_by')->nullable();
               $table->timestamps();
               $table->softDeletes();
          });

          // 13. Tabel Transaksi: t_trans_master
          Schema::create('new_t_trans_master', function (Blueprint $table) {
               $table->id();
               $table->enum('module_source', ['simpanan', 'pinjaman', 'umum']);
               $table->bigInteger('trans_id')->nullable('id_transaksi yang diambil dari tabel t_trans_simpanan, t_trans_pinjaman, t_trans_umum');
               $table->date('tgl_trans');
               $table->text('keterangan')->nullable();
               $table->uuid('created_by')->nullable();
               $table->uuid('updated_by')->nullable();
               $table->uuid('deleted_by')->nullable();
               $table->timestamps();
               $table->softDeletes();
          });

          // 14. Tabel Detail Transaksi: t_trans_detail
          Schema::create('new_t_trans_detail', function (Blueprint $table) {
               $table->id();
               $table->bigInteger('trans_master_id');
               $table->bigInteger('akun_id');
               $table->bigInteger('debet')->default(0);
               $table->bigInteger('kredit')->default(0);
               $table->text('keterangan')->nullable();
               $table->uuid('created_by')->nullable();
               $table->uuid('updated_by')->nullable();
               $table->uuid('deleted_by')->nullable();
               $table->timestamps();
               $table->softDeletes();
          });

          // 15. Tabel Pengaturan Akun
          Schema::create('new_s_pengaturan_akun', function (Blueprint $table) {
               $table->id();
               $table->string('kode_trans', 10)->comment('ambil dari tabel s_jns_trans');
               $table->bigInteger('debet_akun_id');
               $table->bigInteger('kredit_akun_id');
               $table->uuid('created_by')->nullable();
               $table->uuid('updated_by')->nullable();
               $table->uuid('deleted_by')->nullable();
               $table->timestamps();
               $table->softDeletes();
          });
     }

     /**
      * Reverse the migrations.
      *
      * @return void
      */
     public function down()
     {
          // Drop in reverse order to avoid foreign key constraint issues
          Schema::dropIfExists('pengaturan_akun');
          Schema::dropIfExists('t_trans_detail');
          Schema::dropIfExists('t_trans_master');
          Schema::dropIfExists('t_trans_umum');
          Schema::dropIfExists('t_trans_pinjaman');
          Schema::dropIfExists('t_trans_simpanan');
          Schema::dropIfExists('t_pinjaman');
          Schema::dropIfExists('t_simpanan');
          Schema::dropIfExists('s_jns_trans_umum');
          Schema::dropIfExists('s_jns_trans_pinjaman');
          Schema::dropIfExists('s_jns_trans_simpanan');
          Schema::dropIfExists('s_prod_pinjaman');
          Schema::dropIfExists('s_prod_simpanan');
          Schema::dropIfExists('s_akun');
          Schema::dropIfExists('s_anggota');
     }
}
