<?php

namespace App;

class SettingsModel extends ApiBaseModel
{
    protected $table = 'settings';
    public $timestamps = false;

    protected $fillable = [
        'name', 'network_address', 'gateway', 'interface', 'time_interval_for_sending_monitoring_data', 'time_interval_for_scan_ports', 'dos_time_for_check_attacks', 'dos_max_packets_received', 'mask', 'active_system', 'interval_snmp_scan', 'interval_send_saturation','max_bandwidth_saturation','send_mail_saturation','send_mail_dos','send_mail_backdoor','send_mail_inactive_pc'
    ];

}
