<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Teacher extends Authenticatable
{
    use Notifiable;
    public $timestamps = false;
    protected $table = "teachers";
    protected $primaryKey = 't_id';
    public $incrementing = false;

    protected $fillable = [
        't_id',
        'f_name',
        'l_name',
        'address',
        'password',
        'tel',
        'm_id',
        'p_id',
    ];
}
