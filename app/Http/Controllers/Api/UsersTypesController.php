<?php namespace App\Http\Controllers\Api;

use App\user_type_model;

class UsersTypesController extends BaseController
{
    public function index()
    {
        return parent::_index(new user_type_model());
    }

    public function show($id)
    {
        return parent::_show($id, new user_type_model());
    }

    public function store()
    {
        return parent::_store(new user_type_model());
    }

    public function update($id)
    {
        return parent::_update($id, new user_type_model());
    }

    public function destroy($id)
    {
        return parent::_destroy($id, new user_type_model());
    }
}
