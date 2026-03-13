<?php

namespace App\Models\New;

use App\Traits\HasUsersTamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TTransSimpanan extends Model
{
    use SoftDeletes, HasUsersTamps;
    protected $fillable = ['simpanan_id', 'kode_trans', 'tgl_trans', 'jenis_trans', 'nominal', 'keterangan', 'status'];
    protected $table = 't_trans_simpanan';

    public function TSimpanan()
    {
        return $this->belongsTo(TSimpanan::class, 'simpanan_id', 'id');
    }
}
