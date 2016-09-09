<?php

namespace App;

class SettingsModel extends ApiBaseModel
{
    protected $table = 'settings';
    public $timestamps = false;

    protected $fillable = [
        'name', 'network_address', 'gateway', 'interface', 'time_interval_for_sending_monitoring_data', 'time_interval_for_scan_ports', 'dos_time_for_check_attacks', 'dos_max_packets_received', 'mask', 'active_system'
    ];

}
