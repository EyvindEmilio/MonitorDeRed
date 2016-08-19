<?php namespace App\Http\Controllers\Api;

use App\AlertsModel;
use Illuminate\Support\Facades\Session;

class AlertsController extends BaseController
{
    public function index()
    {
        return parent::_index(new AlertsModel());
    }

    public function show($id)
    {
        return parent::_show($id, new AlertsModel());
    }

    public function store()
    {
        return parent::_store(new AlertsModel());
    }

    public function update($id)
    {
        return parent::_update($id, new AlertsModel());
    }

    public function destroy($id)
    {
        return parent::_destroy($id, new AlertsModel());
    }
}
