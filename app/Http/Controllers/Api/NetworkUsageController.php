<?php namespace App\Http\Controllers\Api;

use App\NetworkUsageModel;

class NetworkUsageController extends BaseController
{
    public function index()
    {
        return parent::_index(new NetworkUsageModel());
    }

    public function show($id)
    {
        return parent::_show($id, new NetworkUsageModel());
    }

    public function store()
    {
        return parent::_store(new NetworkUsageModel());
    }

    public function update($id)
    {
        return parent::_update($id, new NetworkUsageModel());
    }

    public function destroy($id)
    {
        return parent::_destroy($id, new NetworkUsageModel());
    }
}
