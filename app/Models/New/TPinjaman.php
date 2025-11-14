<?php

namespace App\Models\New;

use App\Traits\HasUsersTamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TPinjaman extends Model
{
    use SoftDeletes, HasUsersTamps;
    protected $fillable = ['anggota_id', 's_prod_pinjaman_id', 'no_rekening', 'tgl_pencairan', 'plafon', 'jangka_waktu'];
    protected $table = 't_pinjaman';
    public function TTransPinjamanSaldoAwal()
    {
        return $this->hasMany(TTransPinjaman::class, 'pinjaman_id', 'id')->whereIn('kode_trans', ['29', '22']);
    }

    public function TTransPinjaman()
    {
        return $this->hasMany(TTransPinjaman::class, 'pinjaman_id', 'id')->whereIn('kode_trans', ['23', '21']);
    }
}
