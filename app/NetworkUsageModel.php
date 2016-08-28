<?php

namespace App;

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
}
