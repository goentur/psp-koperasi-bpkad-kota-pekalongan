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
}
