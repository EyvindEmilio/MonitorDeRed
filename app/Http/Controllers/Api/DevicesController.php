<?php namespace App\Http\Controllers\Api;

use App\DevicesModel;

class DevicesController extends BaseController
{
    public function index()
    {
        return parent::_index(new DevicesModel());
    }

    public function show($id)
    {
        return parent::_show($id, new DevicesModel());
    }

    public function store()
    {
        return parent::_store(new DevicesModel());
    }

    public function update($id)
    {
        return parent::_update($id, new DevicesModel());
    }

    public function destroy($id)
    {
        return parent::_destroy($id, new DevicesModel());
    }
}
