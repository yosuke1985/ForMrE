<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Tweet extends Model {

    use SoftDeletes;
    protected $table = 'tweets';
    protected $dates = ['deleted_at'];




    protected $fillable = [];

//    protected $hidden = ['created_at'];

    public static $rules = [
        // Validation rules
    ];

    // Relationships

    Public function user(){
        return $this->belongsTo(User::class, "user_id");
    }


}