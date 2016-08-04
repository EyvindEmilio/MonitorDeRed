<?php

namespace App;

class SettingsModel extends ApiBaseModel
{
    protected $table = 'settings';
    public $timestamps = false;

    protected $fillable = [
        'name', 'network_address', 'gateway', 'time_check_network', 'mask', 'active_system'
    ];

}
