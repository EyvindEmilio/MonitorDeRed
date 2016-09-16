<?php

namespace App;

class LogsModel extends ApiBaseModel
{
    protected $table = 'logs';
    public $timestamps = true;

    protected $fillable = ['user', 'ip', 'type', 'description', 'table'];

    public function getSearchFields()
    {
        return ['user', 'ip', 'type', 'description', 'table'];
    }

    public function getOrders()
    {
        return [
            'created_at' => 'DESC'
        ];
    }

    public function getRelationatedFields()
    {
        return ['user'];
    }

    public function user()
    {
        return $this->hasOne('App\User', 'id', 'user');
    }

}
