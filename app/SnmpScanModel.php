<?php

namespace App;

class SnmpScanModel extends ApiBaseModel
{
    protected $table = 'snmp_scan';
    public $timestamps = false;

    protected $fillable = ['ip', 'hardware', 'time_ticks', 'contact', 'machine_name', 'location', 'updated_at'];

}
