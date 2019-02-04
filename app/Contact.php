<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    //
    protected $fillable = [
        'name', 'email', 'mobile','is_fav','comment','user_id'
    ];
}
