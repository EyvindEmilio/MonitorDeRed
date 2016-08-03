<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class user_type_model extends Model
{
    protected $table = 'users_types';
    public $timestamps = false;

    protected $fphpillable = [
        'name', 'description',
    ];

}
