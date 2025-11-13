<?php

namespace App\Models\New;

use App\Models\SatuanKerja;
use App\Traits\HasUsersTamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SAnggota extends Model
{
    use SoftDeletes, HasUsersTamps;
    protected $fillable = ['nik', 'nama', 'alamat', 'kota', 'bidang', 'no_telp', 'status_kepegawaian', 'tgl_daftar'];
    protected $table = 's_anggota';
    public function TSimpanan()
    {
        return $this->hasMany(TSimpanan::class, 'anggota_id', 'id');
    }
    public function TPinjaman()
    {
        return $this->hasMany(TPinjaman::class, 'anggota_id', 'id');
    }
    public function satuanKerja()
    {
        return $this->belongsTo(SatuanKerja::class, 'bidang', 'id');
    }
}
