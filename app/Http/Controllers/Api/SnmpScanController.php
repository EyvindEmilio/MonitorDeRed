<?php namespace App\Http\Controllers\Api;

use App\SnmpScanModel;

class SnmpScanController extends BaseController
{
    public function index()
    {
        return parent::_index(new SnmpScanModel());
    }

    public function show($id)
    {
        return parent::_show($id, new SnmpScanModel());
    }

    public function store()
    {
        return parent::_store(new SnmpScanModel());
    }

    public function update($id)
    {
        return parent::_update($id, new SnmpScanModel());
    }

    public function destroy($id)
    {
        return parent::_destroy($id, new SnmpScanModel());
    }
}
