<?php

namespace App\Models\New;

use App\Traits\HasUsersTamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TSimpanan extends Model
{
    use SoftDeletes, HasUsersTamps;
    protected $fillable = ['anggota_id', 's_prod_simpanan_id', 'no_rekening', 'tgl_daftar'];
    protected $table = 't_simpanan';

    public function TTransSimpanan()
    {
        return $this->hasMany(TTransSimpanan::class, 'simpanan_id', 'id')->whereIn('jenis_trans', ['01', '03', '05', '07', '09']);
    }

    public function TTransTarik()
    {
        return $this->hasMany(TTransSimpanan::class, 'simpanan_id', 'id')->whereIn('jenis_trans', ['02', '04', '06', '08']);
    }

    public function SProdSimpanan()
    {
        return $this->belongsTo(SProdSimpanan::class, 's_prod_simpanan_id', 'id');
    }
}
