<?php namespace App\Application\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

class User extends Eloquent
{
    protected $table = 'user';
    protected $fillable = ['user_name','user_email', 'user_prof', 'user_exp', 'user_phone', 'user_loc' ];
    protected $primaryKey = 'user_id';
}