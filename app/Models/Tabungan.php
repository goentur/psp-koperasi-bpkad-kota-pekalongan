<?php

namespace App\Models;

use App\Traits\HasUsersTamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tabungan extends Model
{
    use SoftDeletes, HasUsersTamps;
    protected $guarded = ['id'];

    public function jenisTabungan()
    {
        return $this->belongsTo(JenisTabungan::class);
    }

    public function setTanggalRequestAttribute($value)
    {
        $this->tanggal_request = $value;
    }

    public function getTanggalRequestAttribute()
    {
        return $this->tanggal_request;
    }
}
