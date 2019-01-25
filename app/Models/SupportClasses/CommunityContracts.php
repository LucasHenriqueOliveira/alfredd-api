<?php

namespace App\Models\SupportClasses;


use Illuminate\Database\Eloquent\Relations\Pivot;
use Traits\PropertiesTranslation;

class CommunityContracts extends Pivot
{
    use PropertiesTranslation;

    protected $table = 'cad_contrato_comu';
    protected $primaryKey = 'cd_contrato_comu';
    protected $sequence = 'sq_cad_contrato_comu';
    const CREATED_AT = null;
    const UPDATED_AT = null;
    protected $errors = [];

    protected $fillable = [
        'cd_contrato_comu',
        'cd_idioma',
        'cd_comunidade',
        'ds_contrato',
        'fg_status'
    ];

}