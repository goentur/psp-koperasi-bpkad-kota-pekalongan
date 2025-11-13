<?php

namespace App\Models\New;

use App\Traits\HasUsersTamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SAnggota extends Model
{
    use SoftDeletes, HasUsersTamps;
    protected $fillable = ['nik', 'nama', 'alamat', 'kota', 'bidang', 'no_telp', 'status_kepegawaian', 'tgl_daftar'];
    protected $table = 's_anggota';
}
