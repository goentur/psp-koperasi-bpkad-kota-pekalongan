<?php

namespace App\Models\New;

use App\Traits\HasUsersTamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SPenganturanAkun extends Model
{
    use SoftDeletes, HasUsersTamps;
    protected $fillable = ['kode_trans', 'module_source', 's_prod_id', 'debet_akun_id', 'kredit_akun_id'];
    protected $table = 's_pengaturan_akun';
}
