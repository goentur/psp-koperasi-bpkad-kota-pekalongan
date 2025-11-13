<?php

namespace App\Models\New;

use App\Traits\HasUsersTamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TTransUmum extends Model
{
    use SoftDeletes, HasUsersTamps;
    protected $fillable = ['kode_trans', 'tgl_trans', 'nominal', 'keterangan'];
    protected $table = 't_trans_umum';
}
