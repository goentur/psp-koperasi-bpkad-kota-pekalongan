<?php

namespace App\Models\New;

use App\Traits\HasUsersTamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SJnsTrans extends Model
{
    use SoftDeletes, HasUsersTamps;
    protected $primaryKey = 'kode_trans';
    public $incrementing = false;
    protected $fillable = ['kode_trans', 'module_source', 'nama', 'mutasi', 'keterangan'];
    protected $table = 's_jns_trans';
}
