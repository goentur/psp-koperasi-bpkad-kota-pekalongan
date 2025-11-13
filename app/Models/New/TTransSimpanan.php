<?php

namespace App\Models\New;

use App\Traits\HasUsersTamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TTransSimpanan extends Model
{
    use SoftDeletes, HasUsersTamps;
    protected $fillable = ['simpanan_id', 'kode_trans', 'tgl_trans', 'jenis_trans', 'nominal', 'keterangan'];
    protected $table = 't_trans_simpanan';
}
