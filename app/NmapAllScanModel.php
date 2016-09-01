<?php

namespace App;

class NmapAllScanModel extends ApiBaseModel
{
    protected $table = 'nmap_all_scan';
    public $timestamps = false;

    protected $fillable = ['ip', 'mac', 'manufacturer'];

}
