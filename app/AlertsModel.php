<?php

namespace App;

use Illuminate\Support\Facades\DB;

class AlertsModel extends ApiBaseModel
{
    protected $table = 'alerts';
    public $timestamps = true;

    protected $fillable = [
        'type', 'ip_src', 'ip_dst'
    ];

    public function getOrders()
    {
        return [
            'created_at' => 'DESC'
        ];
    }

    public static function getAlerts($start_date, $end_date)
    {
        return AlertsModel::where('created_at', '>=', $start_date)->where('created_at', '<=', $end_date)->get();
    }

    public static function getNumberAlertsByTypes($start_date, $end_date)
    {
        return DB::select('SELECT alerts.type, COUNT(*) as suma FROM alerts WHERE created_at >="' . $start_date . '" AND created_at<="' . $end_date . '" GROUP BY alerts.type');
    }
}
