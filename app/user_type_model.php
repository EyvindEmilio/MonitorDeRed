<?php

namespace App;

class user_type_model extends ApiBaseModel
{
    protected $table = 'users_types';
    public $timestamps = false;

    protected $fillable = [
        'name', 'description',
    ];

    public function getSearchFields()
    {
        return ['name'];
    }

}
