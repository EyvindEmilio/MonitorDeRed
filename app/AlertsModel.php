<?php

namespace App;

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
}
