<?php

namespace App\Models\New;

use App\Traits\HasUsersTamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SProdSimpanan extends Model
{
    use SoftDeletes, HasUsersTamps;
    protected $primaryKey = 'id';
    public $incrementing = false;
    public $keyType = 'char';
    protected $fillable = ['id', 'nama', 'admin', 'bunga', 'keterangan'];
    protected $table = 's_prod_simpanan';
}
