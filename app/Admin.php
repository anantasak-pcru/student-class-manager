<?php

namespace App;

//use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable 
{
    use Notifiable;
    public $timestamps = false;
    protected $table = "admins";
    protected $primaryKey = 'admin_id';
    public $incrementing = false;
    protected $fillable = [
        'admin_id',
        'f_name',
        'l_name', 
        'password',
        'p_id',
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
}
