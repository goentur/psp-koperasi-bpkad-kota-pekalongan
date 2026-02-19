<?php

namespace App\Models;

use App\Traits\HasUsersTamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pinjaman extends Model
{
    use SoftDeletes, HasUsersTamps;
    protected $guarded = ['id'];
    protected $table = 't_pinjaman';

    public function angsuran()
    {
        return $this->hasMany(Angsuran::class);
    }
}
