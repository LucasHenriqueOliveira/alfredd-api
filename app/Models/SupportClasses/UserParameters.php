<?php

namespace App\Models\SupportClasses;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class UserParameters extends Model
{
    protected $table = "cad_usu_par";
    protected $primaryKey = "cd_usu_par";
    const CREATED_AT = 'dt_ultimo_acesso';
    const UPDATED_AT = 'dt_ultimo_acesso';

    protected $fillable = [
        'cd_usu_par',
        'cd_usuario',
        'cd_app',
        'ds_senha',
        'ds_token',
        'ds_codigo_integra_app',
        'nu_tentativas',
        'nu_ip',
        'nu_cpf',
        'fg_senha_segura',
        'fg_status',
        'dt_senha_alterada',
        'dt_ultimo_acesso',
    ];

    protected $attributes = [
        'cd_app' => null,
        'ds_token' => null,
        'ds_codigo_integra_app' => null,
        'nu_ip' => null,
        'nu_cpf' => null,
        'fg_status' => 'I',
        'fg_senha_segura' => 'S',
        'dt_ultimo_acesso' => null,
    ];

    public function hasSecurePassword()
    {
        return ($this->fg_senha_segura==='S');
    }

    public function setPasswordAttribute($value)
    {
        $this->dt_senha_alterada = Carbon::now();
        $this->fg_senha_segura = 'S';
        $this->nu_tentativas = 0;
        $this->ds_senha = md5($value);
    }
}