<?php namespace App\Http\Controllers\Api;

use App\User;

class UsersController extends BaseController
{
    public function index()
    {
        return parent::_index(new User());
    }

    public function show($id)
    {
        return parent::_show($id, new User());
    }

    public function store()
    {
        return parent::_store(new User());
    }

    public function update($id)
    {
        return parent::_update($id, new User());
    }

    public function destroy($id)
    {
        return parent::_destroy($id, new User());
    }
}
