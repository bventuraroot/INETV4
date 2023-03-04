<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermissionCompany extends Model
{
    use HasFactory;
    protected $table = "permission_company";// <-- El nombre personalizado
    protected $fillable = [
        'user_id',
        'company_id',
        'state'
    ];
}
