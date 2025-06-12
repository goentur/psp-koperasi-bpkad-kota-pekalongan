<?php

namespace App\Models;

use App\Traits\HasUsersTamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TempatSaldo extends Model
{
    use SoftDeletes, HasUsersTamps;
    protected $guarded = ['id'];
}
