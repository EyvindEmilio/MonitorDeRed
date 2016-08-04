<?php

namespace App;

class AreasModel extends ApiBaseModel
{
    protected $table = 'areas';
    public $timestamps = false;

    protected $fillable = [
        'name', 'description',
    ];

    public function getSearchFields()
    {
        return ['name'];
    }

}
