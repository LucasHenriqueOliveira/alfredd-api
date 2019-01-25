<?php

namespace App\Models\SupportClasses;

use Illuminate\Database\Eloquent\Model;

class CompanyStatus extends Model
{

    protected $table = 'cad_emp_status';
    protected $primaryKey = 'cd_emp_status';
    public $sequence = 'sq_cad_emp_status';
    const CREATED_AT = 'dt_emissao';
    const UPDATED_AT = 'dt_alteracao_situacao';
    protected $errors = [];

    protected $fillable = [
        'cd_emp_status',
        'cd_emp',
        'fg_status',
        'fg_bloqueio'
    ];

}