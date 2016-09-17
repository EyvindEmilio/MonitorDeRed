<?php

namespace App;

use DateInterval;
use DatePeriod;
use DateTime;
use Illuminate\Support\Facades\DB;

class NetworkUsageModel extends ApiBaseModel
{
    protected $table = 'network_usage';
    public $timestamps = true;

    protected $fillable = [
        'ip', 'size', 'len', 'date'
    ];

    public function getOrders()
    {
        return [
            'date' => 'DESC'
        ];
    }

    public static function getConsumo()
    {
        return DB::select('SELECT  (SELECT SUM((SELECT SUM(network_usage.size) AS network_usage FROM network_usage WHERE network_usage.ip = devices.ip)) as network_usage from devices WHERE area = areas.id) AS network_usage ,  areas.name as area, areas.id AS id FROM areas');
    }

    public static function getConsumoUnknown()
    {
        return DB::select('SELECT SUM(network_usage.size) AS network_usage, "Dispositivos no registrados" as area, 0  as id  FROM network_usage WHERE NOT EXISTS (SELECT devices.ip FROM devices WHERE network_usage.ip = devices.ip)');
    }

    public static function getConsumoPerDate($start_date, $end_date)
    {
        return DB::select('SELECT  (SELECT SUM((SELECT SUM(network_usage.size) AS network_usage FROM network_usage WHERE network_usage.ip = devices.ip AND network_usage.date >= "' . $start_date . '" AND network_usage.date <= "' . $end_date . '" )) as network_usage from devices WHERE area = areas.id) AS network_usage ,  areas.name as area, areas.id AS id FROM areas');
    }

    public static function getConsumoAreaPerDate($id_area, $start_d, $end_d)
    {
        $id = $id_area;//id_area

        $sd = $start_d;
        $ed = $end_d;

        $start_date = new DateTime($sd);
        $end_date = (new DateTime($ed))->modify('+1 day');
        $date_range = new DatePeriod($start_date, new DateInterval('P1D'), $end_date);
        $consumo = [];

        foreach ($date_range as $date) {
            $current_date = $date->format('Y-m-d');
            if ($id != 0) {
                $size = NetworkUsageModel::where('date', $current_date)
                    ->join('devices', 'devices.ip', '=', 'network_usage.ip')
                    ->where('devices.area', $id)->sum('size');
            } else {
                $size = DB::select('SELECT SUM(network_usage.size) AS size FROM network_usage WHERE NOT EXISTS (SELECT devices.ip FROM devices WHERE network_usage.ip = devices.ip) AND network_usage.date = "' . $current_date . '"');
                $size = $size[0]->size;
            }
            array_push($consumo, ['date' => $current_date, 'size' => $size]);
        }
        return $consumo;
    }

    public static function getConsumoAreaIpPerDate($id_area, $start_d, $end_d)
    {
        $id = $id_area;//id_area

        $sd = $start_d;
        $ed = $end_d;

        $start_date = new DateTime($sd);
        $end_date = (new DateTime($ed))->modify('+1 day');
        $date_range = new DatePeriod($start_date, new DateInterval('P1D'), $end_date);

        if ($id_area != 0) {
            $list_devices = DevicesModel::where('area', $id_area)->get()->toArray();
        } else {
            $list_devices = DB::select('SELECT network_usage.ip as ip, "Desconocido" As name FROM network_usage WHERE NOT EXISTS (SELECT devices.ip FROM devices WHERE network_usage.ip = devices.ip) AND network_usage.date >= "' . $start_date->format('Y-m-d') . '" AND network_usage.date <= "' . $end_date->format('Y-m-d') . '" GROUP BY network_usage.ip');
            $list_devices = (array)$list_devices;
        }
        $list_date_ip = array();

        for ($i = 0; $i < sizeof($list_devices); $i++) {
            $list_devices[$i] = (array)$list_devices[$i];
            $consumo_ip = array();
            $consumo_ip['ip'] = $list_devices[$i]['ip'];
            $consumo_ip['name'] = $list_devices[$i]['name'];
            $consumo_ip['data'] = array();

            foreach ($date_range as $date) {
                $current_date = $date->format('Y-m-d');
                $consumo = array();
                $size = NetworkUsageModel::where('date', $current_date)
                    ->where('ip', $list_devices[$i]['ip'])->sum('size');

                $consumo['date'] = $current_date;
                $consumo['size'] = $size ? $size : 0;
                array_push($consumo_ip['data'], $consumo);
            }
            array_push($list_date_ip, $consumo_ip);
        }

        return $list_date_ip;
    }

    /*public static function getConsumoAreaIpPerDate($id_area, $start_d, $end_d)
    {
        $id = $id_area;//id_area

        $sd = $start_d;
        $ed = $end_d;

        $start_date = new DateTime($sd);
        $end_date = (new DateTime($ed))->modify('+1 day');
        $date_range = new DatePeriod($start_date, new DateInterval('P1D'), $end_date);

        $list_devices = DevicesModel::where('area', $id_area)->get()->toArray();
        $list_date_ip = array();

        foreach ($date_range as $date) {
            $current_date = $date->format('Y-m-d');
            $consumo_date = array();
            $consumo_date['date'] = $current_date;
            $consumo_date['data'] = array();
            $consumo = array();
            for ($i = 0; $i < sizeof($list_devices); $i++) {
                $size = NetworkUsageModel::where('date', $current_date)
                    ->where('ip', $list_devices[$i]['ip'])->sum('size');
                $consumo['ip'] = $list_devices[$i]['ip'];
                $consumo['size'] = $size ? $size : 0;
                array_push($consumo_date['data'], $consumo);
            }
            array_push($list_date_ip, $consumo_date);
        }

        return $list_date_ip;
    }*/
}
