<?php namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use DateTime;
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
        if (Input::has('start_date') && Input::has('end_date')) {
            $start_date = (new DateTime(Input::get('start_date')))->modify('+1 day')->format('Y-m-d');
            $end_date = (new DateTime(Input::get('end_date')))->modify('+1 day')->format('Y-m-d');
            $response = $response->where('created_at', '>=', $start_date)->where('created_at', '<=', $end_date);
        }
        $response = $response->paginate($page_size)->toArray();

        if (isset($_model->image_fields)) {
            $image_fields = $_model->image_fields;
            for ($i = 0; $i < sizeof($image_fields); $i++) {
                $field = $image_fields[$i]['field'];
                $path = $image_fields[$i]['path'];
                for ($j = 0; $j < sizeof($response['data']); $j++) {
                    if (isset($response['data'][$j][$field]) && $response['data'][$j][$field] != null && $response['data'][$j][$field] != '') {
                        $response['data'][$j][$field] = 'http://' . $_SERVER['HTTP_HOST'] . '/' . $path . '/' . $response['data'][$j][$field];
                    } else {
                        $response['data'][$j][$field] = null;
                    }
                }
            }
        }

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
        $response = $response->find($id)->toArray();

        if (isset($_model->image_fields)) {
            $image_fields = $_model->image_fields;
            for ($i = 0; $i < sizeof($image_fields); $i++) {
                $field = $image_fields[$i]['field'];
                $path = $image_fields[$i]['path'];
                if (isset($response[$field]) && $response[$field] != null && $response[$field] != '') {
                    $response[$field] = 'http://' . $_SERVER['HTTP_HOST'] . '/' . $path . '/' . $response[$field];
                } else {
                    $response[$field] = null;
                }
            }
        }
        return Response::create($response);
    }

    public function _store(Model $_model)
    {
        if (Input::has('id')) {
            return $this->_update(Input::get('id'), $_model);
        }
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
            $input = Input::all();
            $input = $this->parseImageFields($_model, $input);
            $data = $_model->create($input);
            return Response::create($data);
        }
        //} catch (QueryException $e) {
        //				return Response::create(['detail' => 'No se pudo agregar registro'], 401);
        //	}
    }

    public function parseImageFields($_model, $input)
    {
        if (isset($_model->image_fields)) {
            $image_fields = $_model->image_fields;
            for ($i = 0; $i < sizeof($image_fields); $i++) {
                $field = $image_fields[$i]['field'];
                if (!Input::file($field)) break;
                $extension = Input::file($field)->getClientOriginalExtension();
                $path = $image_fields[$i]['path'];
                $new_name = uniqid("img_") . '.' . $extension;
                Input::file($field)->move($path, $new_name);
                $input[$field] = $new_name;
            }
        }
        return $input;
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
                $input = Input::all();
                $input = $this->parseImageFields($_model, $input);
                /** @noinspection PhpUndefinedMethodInspection */
                $_model->find($id)->update($input);
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
