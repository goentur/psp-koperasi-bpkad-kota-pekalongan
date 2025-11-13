<?php

namespace App\Models\New;

use App\Traits\HasUsersTamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TTransMaster extends Model
{
    use SoftDeletes, HasUsersTamps;
    protected $fillable = ['module_source', 'trans_id', 'tgl_trans', 'keterangan'];
    protected $table = 't_trans_master';
}
