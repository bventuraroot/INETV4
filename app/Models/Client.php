<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'firstname',
        'secondname',
        'firtslastname',
        'secondlastname',
        'comercial_name',
        'tel1',
        'tel2',
        'email',
        'address',
        'giro',
        'nit',
        'tpersona',
        'legal',
        'birthday',
        'empresa',
        'companyselected',
        'contribuyente',
        'tipoContribuyente',
        'country',
        'departament',
        'municipio',
        'acteconomica'
    ];
}
