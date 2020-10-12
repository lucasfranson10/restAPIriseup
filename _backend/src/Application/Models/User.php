<?php namespace App\Application\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

class User extends Eloquent
{
    protected $table = 'user';
    protected $fillable = ['user_name','user_email'];
    protected $primaryKey = 'user_id';
}