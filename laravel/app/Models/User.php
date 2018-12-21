<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class User extends Model
{

    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $table = 'users';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */





    protected $fillable = [
        'name', 'email',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];


    Public function tweet(){
        return $this->hasMany(Tweet::class);
    }


    Public function followUsers(){
        return $this->hasMany(Follow::class, "follow_user_id");
    }

    Public function followedUsers(){
        return $this->hasMany(Follow::class, "followed_user_id");
    }

}
