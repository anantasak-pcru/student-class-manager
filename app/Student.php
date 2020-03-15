<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Student extends Authenticatable
{
    use Notifiable;
    public $timestamps = false;
    protected $table = "students";
    protected $primaryKey = 'std_id';
    public $incrementing = false;

    protected $fillable = [
        'std_id',
        'l_name',
        'f_name',
        'address',
        'tel',
        'password',
        'm_id'
    ];
}
