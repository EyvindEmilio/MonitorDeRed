<?php

namespace App;

class DevicesModel extends ApiBaseModel
{
    protected $table = 'devices';
    public $timestamps = true;

    protected $fillable = [
        'name', 'ip', 'mac', 'status', 'description', 'area', 'device_type', 'notes'
    ];

    public function getSearchFields()
    {
        return ['name', 'mac', 'manufacturer'];
    }

    public function getRelationatedFields()
    {
        return ['area', 'device_type'];
    }

    public function area()
    {
        return $this->hasOne('App\AreasModel', 'id', 'area');
    }

    public function device_type()
    {
        return $this->hasOne('App\DeviceTypesModel', 'id', 'device_type');
    }

}
