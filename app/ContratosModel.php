<?php namespace App;


class ContratosModel extends ApiBaseModel
{

    public $fillable = ['numero', 'numero_contrato', 'contrato', 'info_contrato', 'docente', 'gestion_academica'];
    public $timestamps = true;
    protected $table = 'contratos';


    public function getRelationatedFields()
    {
        return ['docente', 'gestion_academica'];
    }

    public function getSearchFields()
    {
        return ['numero', 'contrato', 'info_contrato'];
    }

    public function docente()
    {
        return $this->hasOne('App\DocenteModel', 'id', 'docente');
    }

    public function gestion_academica()
    {
        return $this->hasOne('App\GestionAcademicasModel', 'id', 'gestion_academica');
    }

    public function getOrders()
    {
        return [
            'docente' => 'ASC'
        ];
    }
}
