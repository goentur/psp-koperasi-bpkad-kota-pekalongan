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
}
