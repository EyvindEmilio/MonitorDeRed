<?php namespace App\Http\Controllers\Api;

use App\AreasModel;

class AreasController extends BaseController
{
    public function index()
    {
        return parent::_index(new AreasModel());
    }

    public function show($id)
    {
        return parent::_show($id, new AreasModel());
    }

    public function store()
    {
        return parent::_store(new AreasModel());
    }

    public function update($id)
    {
        return parent::_update($id, new AreasModel());
    }

    public function destroy($id)
    {
        return parent::_destroy($id, new AreasModel());
    }
}
