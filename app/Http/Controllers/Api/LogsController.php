<?php namespace App\Http\Controllers\Api;

use App\LogsModel;

class LogsController extends BaseController
{
    public function index()
    {
        return parent::_index(new LogsModel());
    }

    public function show($id)
    {
        return parent::_show($id, new LogsModel());
    }

    public function store()
    {
        return parent::_store(new LogsModel());
    }

    public function update($id)
    {
        return parent::_update($id, new LogsModel());
    }

    public function destroy($id)
    {
        return parent::_destroy($id, new LogsModel());
    }
}
