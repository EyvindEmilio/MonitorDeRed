<?php namespace App\Http\Controllers\Api;

use App\AreasModel;

class MonitoringController extends BaseController
{
    public function getListDevices()
    {
        return 2;
    }

    public function getInfo($IP)
    {
        return 2;
    }

    public function scanPorts($IP = null)
    {
        return 2;
    }
}
