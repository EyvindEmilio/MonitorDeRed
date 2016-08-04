<?php namespace App\Http\Controllers\Api;

use App\DeviceTypesModel;

class DeviceTypesController extends BaseController
{
    public function index()
    {
        return parent::_index(new DeviceTypesModel());
    }

    public function show($id)
    {
        return parent::_show($id, new DeviceTypesModel());
    }

    public function store()
    {
        return parent::_store(new DeviceTypesModel());
    }

    public function update($id)
    {
        return parent::_update($id, new DeviceTypesModel());
    }

    public function destroy($id)
    {
        return parent::_destroy($id, new DeviceTypesModel());
    }
}
