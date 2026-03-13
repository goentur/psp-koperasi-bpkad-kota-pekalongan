<?php

namespace App\Models\New;

use App\Traits\HasUsersTamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TTransPinjaman extends Model
{
    use SoftDeletes, HasUsersTamps;
    protected $fillable = ['pinjaman_id', 'kode_trans', 'tgl_trans', 'jenis_trans', 'nominal', 'keterangan', 'status'];
    protected $table = 't_trans_pinjaman';

    public function TPinjaman()
    {
        return $this->belongsTo(TPinjaman::class, 'pinjaman_id', 'id');
    }
}
