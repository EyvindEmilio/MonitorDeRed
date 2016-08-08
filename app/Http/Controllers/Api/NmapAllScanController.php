<?php namespace App\Http\Controllers\Api;

use App\NmapAllScanModel;
use Illuminate\Support\Facades\Session;

class NmapAllScanController extends BaseController
{
    public function index()
    {
        return parent::_index(new NmapAllScanModel());
    }

    public function show($id)
    {
        return parent::_show($id, new NmapAllScanModel());
    }

    public function store()
    {
        return parent::_store(new NmapAllScanModel());
    }

    public function update($id)
    {
        return parent::_update($id, new NmapAllScanModel());
    }

    public function destroy($id)
    {
        return parent::_destroy($id, new NmapAllScanModel());
    }
}
