<?php

namespace App\Models\SupportClasses;

use App\Models\Community;
use App\Models\Contract;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Traits\PropertiesTranslation;

class CompanyParent extends Model
{
    use PropertiesTranslation;

    protected $table = 'cad_emp';
    protected $primaryKey = 'cd_emp';
    public $sequence = 'sq_cad_emp';
    const CREATED_AT = 'dt_cad';
    const UPDATED_AT = null;
    protected $errors = [];

    protected $fillable = [
        'cd_emp',
        'cd_usu_cad',
        'cd_usu_adesao',
        'cd_app',
        'cd_sap_number',
        'cd_termo_contrato',
        'cd_cad_emp_par',
        'ds_emp',
        'fg_status',
        'fg_pre_cadastro',
        'fg_exp_sap',
        'fg_adesao',
        'fg_origem',
        'nu_dias_aviso_susp',
        'nu_dias_aviso_canc',
        'nu_dias_aviso_nova',
        'nu_dias_aviso_acei',
        'nu_control_tecnico',
        'ds_sap_message',
        'ds_sap_contrato',
        'ds_sap_nota_servico',
        'ds_sap_pedido',
        'ds_caixa_postal',
        'ds_type_img_faccol',
        'tp_pla_contrato',
        'tp_web_service',
        'tp_emp',
        'tt_img_faccol',
        'sg_estado_cob',
        'ip_adesao',
        'formato_pagina_faccol',
        'ds_codigo_integra_app',
        'dt_adesao',
        'dt_cad',
    ];

    public function subsidiaries()
    {
        return $this->hasMany(CompanySubsidiary::class, 'cd_emp', 'cd_emp')->where('fg_status', 'L')->get();
    }

    public function users()
    {
        return $this->hasMany(User::class, 'cd_emp', 'cd_emp')->where('fg_status', 'L')->get();
    }

    public function communities()
    {
        return $this->hasMany(Community::class, 'cd_emp', 'cd_emp')->get();
    }

    public function contracts()
    {
        return $this->hasMany(Contract::class, 'cd_emp', 'cd_emp')->where('fg_status', 'L')->get();
    }

    public static function findById($id)
    {
        return self::where('cd_emp', $id)->first();
    }

}