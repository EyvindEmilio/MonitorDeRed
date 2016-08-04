<?php namespace App\Http\Controllers\Api;

use App\SettingsModel;
use Illuminate\Support\Facades\Session;

class SettingsController extends BaseController
{
    public function index()
    {
        return parent::_index(new SettingsModel());
    }

    public function show($id)
    {
        return parent::_show($id, new SettingsModel());
    }

    public function store()
    {
        return parent::_store(new SettingsModel());
    }

    public function update($id)
    {
        Session::flash('flash_message', 'Se ha modificado configuraciones del sistema');
        return parent::_update($id, new SettingsModel());
    }

    public function destroy($id)
    {
        return parent::_destroy($id, new SettingsModel());
    }
}
