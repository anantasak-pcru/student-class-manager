<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Check extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'chk_id',
        'date',
        'detail',
        'status',
        'cr_id'
    ];
}
