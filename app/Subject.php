<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $table = 'subjects';
    protected $primaryKey = 's_id';
    public $timestamps = false;
    protected $fillable = [
        's_id',
        'name',
        't_id',
    ];
}
