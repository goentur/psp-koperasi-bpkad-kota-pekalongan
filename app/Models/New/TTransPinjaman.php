<?php

namespace App\Models\New;

use App\Traits\HasUsersTamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TTransPinjaman extends Model
{
    use SoftDeletes, HasUsersTamps;
    protected $fillable = ['pinjaman_id', 'kode_trans', 'tgl_trans', 'jenis_trans', 'nominal', 'keterangan'];
    protected $table = 't_trans_pinjaman';
}
