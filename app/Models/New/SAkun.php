<?php

namespace App\Models\New;

use App\Traits\HasUsersTamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SAkun extends Model
{
    use SoftDeletes, HasUsersTamps;
    public $incrementing = false;
    protected $fillable = ['id', 'nama', 'jenis', 'kolom', 'keterangan'];
    protected $table = 's_akun';
}
