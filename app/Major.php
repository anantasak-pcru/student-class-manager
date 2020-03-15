<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Major extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'm_id',
        'name',
        'fac_id'
    ];
}
