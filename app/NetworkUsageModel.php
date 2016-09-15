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
            $size = NetworkUsageModel::where('date', $current_date)
                ->join('devices', 'devices.ip', '=', 'network_usage.ip')
                ->where('devices.area', $id)->sum('size');
            array_push($consumo, ['date' => $current_date, 'size' => $size]);
        }
        return $consumo;
    }
}
