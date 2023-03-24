<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'addres',
        'number_phone',
        'email',
        'nit',
        'ncr',
        'cuenta_no',
        'tipoContribuyente',
        'tipoEstablecimiento',
        'logo',
        'pais_id',
        'departamento_id',
        'municipio_id',
        'actividad_id'
    ];
}
