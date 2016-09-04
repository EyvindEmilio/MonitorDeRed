<?php

namespace App;

class DeviceTypesModel extends ApiBaseModel
{
    protected $table = 'device_types';
    public $timestamps = false;

    protected $fillable = [
        'name', 'image', 'manufacturer', 'description',
    ];

    public $image_fields = [['field' => 'image', 'path' => 'images/device_types']];

    public function getSearchFields()
    {
        return ['name', 'manufacturer'];
    }

}
