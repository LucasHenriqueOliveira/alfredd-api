<?php

namespace App\Models\SupportClasses;

use Illuminate\Database\Eloquent\Model;
use Traits\PropertiesTranslation;

class CompanySubsidiary extends Model
{
    use PropertiesTranslation;

    protected $table = 'cad_emp_fl';
    protected $primaryKey = 'cd_emp_fl';
    public $sequence = 'sq_cad_emp_fl';
    const CREATED_AT = 'dt_cadastro';
    const UPDATED_AT = null;
    protected $errors = [];

    protected $fillable = [
        'cd_emp_fl',
        'cd_emp',
        'cd_erp_hub',
        'cd_app',
        'ds_razaosocial',
        'ds_nomefantasia_fl',
        'ds_reduzida',
        'ds_codigo_integra_app',
        'fg_principal',
        'fg_status',
        'nu_cnpj',
        'nu_inscricao_municipal',
        'nu_inscricao_estadual',
        'ds_endereco',
        'ds_numero',
        'ds_complemento',
        'ds_bairro',
        'ds_cidade',
        'sg_estado',
        'ds_pais',
        'nu_cep',
        'ds_ddd',
        'ds_fone',
        'ds_ramal',
        'ds_endereco_cob',
        'ds_numero_cob',
        'ds_complemento_cob',
        'ds_bairro_cob',
        'ds_cidade_cob',
        'sg_estado_cob',
        'ds_pais_cob',
        'nu_cep_cob',
        'ds_ddd_cob',
        'ds_contato_cob',
        'ds_email_cob',
        'ds_departamento_cob',
        'ds_cargo_cob',
        'fg_modalidade_pagamento',
        'ds_ramal_cob',
        'ds_fone_cob',
        'ds_email_cob1',
        'ds_email_cob2',
        'nu_ip_cadastro',
        'dt_cadastro'
    ];

    public static function findById($id)
    {
        return self::where('cd_emp_fl', $id)->first();
    }

}