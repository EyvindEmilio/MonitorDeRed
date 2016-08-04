<?php

namespace App;

class DeviceTypesModel extends ApiBaseModel
{
    protected $table = 'device_types';
    public $timestamps = false;

    protected $fillable = [
        'name', 'manufacturer', 'description',
    ];

    public function getSearchFields()
    {
        return ['name', 'manufacturer'];
    }

}
