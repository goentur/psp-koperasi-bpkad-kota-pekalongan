<?php

namespace App\Models\New;

use App\Traits\HasUsersTamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TTransDetail extends Model
{
    use SoftDeletes, HasUsersTamps;
    protected $fillable = ['trans_master_id', 'akun_id', 'debet', 'kredit', 'keterangan'];
    protected $table = 't_trans_detail';
}
