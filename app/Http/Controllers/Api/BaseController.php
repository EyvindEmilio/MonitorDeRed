<?php namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;

class BaseController extends Controller
{
    public function _index(Model $_model)
    {
        /** @noinspection PhpUndefinedMethodInspection */
        $input = Input::all();
        $page_size = 20;
        /** @noinspection PhpUndefinedMethodInspection */
        if (Input::has('page_size')) {
            $page_size = Input::get('page_size');
        }
        $response = $this->indexShowCustom($_model, $input);
        $response = $response->paginate($page_size);
        return Response::create($response);
    }

    public function indexShowCustom(Model $_model, $input = array(), $method = 'GET')
    {
        /** @noinspection PhpUndefinedMethodInspection */
        $is_search = Input::has('search') ? (Input::get('search') != "" ? true : false) : false;
        if (method_exists($_model, 'getRelationatedFields')) {
            $response = $_model->with($_model->getRelationatedFields());
        } else {
            $response = $_model;
        }
        if ($method == 'GET') {
            foreach ($input as $key => $value) {
                /** @noinspection PhpUndefinedMethodInspection */
                if (Schema::hasColumn($_model->getTable(), $key) && $value != "") {
                    $response = $response->where($key, $value);
                }
            }

            if ($is_search && method_exists($_model, 'getSearchFields')) {
                $search = Input::get('search');
                $search_fields = $_model->getSearchFields();
                $response = $response->where(function ($q) use ($search_fields, $search) {
                    foreach ($search_fields as $key => $value) {
                        if ($key == 0) {
                            /** @noinspection PhpUndefinedMethodInspection */
                            $q = $q->where($value, 'LIKE', '%' . $search . '%');
                        } else {
                            /** @noinspection PhpUndefinedMethodInspection */
                            $q = $q->orWhere($value, 'LIKE', '%' . $search . '%');
                        }
                    }
                });
            }
        }
        if (method_exists($_model, 'getOrders')) {
            $orders = $_model->getOrders();
            foreach ($orders as $key => $value) {
                /** @noinspection PhpUndefinedMethodInspection */
                $response = $response->orderBy($key, $value);
            }
        }

        return $response;
    }

    public function _show($id, Model $_model)
    {
        /** @noinspection PhpUndefinedMethodInspection */
        $input = Input::all();
        $response = $this->indexShowCustom($_model, $input, 'SHOW');
        $response = $response->find($id);
        return Response::create($response);
    }

    public function _store(Model $_model)
    {
        //	try {
        if (isset($_model->rules)) {
            /** @noinspection PhpUndefinedMethodInspection */
            $validation = Validator::make(Input::all(), $_model->rules);
            /** @noinspection PhpUndefinedMethodInspection */
            if ($validation->fails()) {
                /** @noinspection PhpUndefinedMethodInspection */
                $mess = $validation->messages();
                return Response::create($mess, 401);
            } else {
                /** @noinspection PhpUndefinedMethodInspection */
                $data = $_model->create(Input::all());
                return Response::create($data);
            }
        } else {
            /** @noinspection PhpUndefinedMethodInspection */
            $data = $_model->create(Input::all());
            return Response::create($data);
        }
        //} catch (QueryException $e) {
        //				return Response::create(['detail' => 'No se pudo agregar registro'], 401);
        //	}
    }

    public function _update($id, Model $_model)
    {
        try {
            if (isset($_model->rules)) {
                $rules = array();
                /*foreach ($_model->rules as $key => $value) { // comentado por calidacion de duplicado en la misma instancia
                                if (Input::has($key)) {
                                                $rules[$key] = $value;
                                }
                }*/
                /** @noinspection PhpUndefinedMethodInspection */
                $validation = Validator::make(Input::all(), $rules);
                /** @noinspection PhpUndefinedMethodInspection */
                if ($validation->fails()) {
                    /** @noinspection PhpUndefinedMethodInspection */
                    $mess = $validation->messages();
                    return Response::create($mess, 401);
                } else {
                    /** @noinspection PhpUndefinedMethodInspection */
                    $_model->find($id)->update(Input::all());
                    return Response::create($_model->find($id));
                }
            } else {
                /** @noinspection PhpUndefinedMethodInspection */
                $_model->find($id)->update(Input::all());
                return Response::create($_model->find($id));
            }
        } catch (QueryException $e) {
            return Response::create(['detail' => 'No se pudo modificar'], 401);
        }
    }

    public function _destroy($id, Model $_model)
    {
        try {
            $_model->find($id)->delete();
            return Response::create(['detail' => 'Registro eliminaro']);
        } catch (QueryException $e) {
            return Response::create(['detail' => 'No se pudo eliminar'], 401);
        }
    }
}
