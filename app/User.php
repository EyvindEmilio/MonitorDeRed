<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;
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

    public static function isAdmin()
    {
        if (Auth::check()) {
            return Auth::user()['user_type'] == 1;
        } else {
            return false;
        }
    }

    public static function isAdminCollaborator()
    {
        if (Auth::check()) {
            return (Auth::user()['user_type'] == 1) || (Auth::user()['user_type'] == 2);
        } else {
            return false;
        }
    }

    public static function isJefe()
    {
        if (Auth::check()) {
            return (Auth::user()['user_type'] == 3);
        } else {
            return false;
        }
    }

    public static function isCollaborator()
    {
        if (Auth::check()) {
            return (Auth::user()['user_type'] == 2);
        } else {
            return false;
        }
    }

    public static function isJefeOrCollaborator()
    {
        if (Auth::check()) {
            return (Auth::user()['user_type'] == 2) || (Auth::user()['user_type'] == 3);
        } else {
            return false;
        }
    }

    public function getSearchFields()
    {
        return ['first_name', 'last_name', 'email'];
    }
}
