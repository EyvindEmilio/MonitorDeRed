<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'image', 'user_type', 'status', 'email', 'password',
    ];

    public $image_fields = [['field' => 'image', 'path' => 'images/users']];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function user_type()
    {
        return $this->hasOne(user_type_model::class, 'id', 'user_type');
    }

    public function getRelationatedFields()
    {
        return ['user_type'];
    }

    public function setPasswordAttribute($password)
    {
        /** @noinspection PhpUndefinedMethodInspection */
        $this->attributes['password'] = Hash::make($password);
    }
}
