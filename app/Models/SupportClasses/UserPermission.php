<?php

namespace App\Models\SupportClasses;

use Illuminate\Database\Eloquent\Model;

class UserPermission extends Model
{
    protected $table = "cad_usu_perm";
    protected $primaryKey = "cd_usu_perm";
    public $sequence = 'sq_cad_usu_perm';
    const CREATED_AT = null;
    const UPDATED_AT = null;

    protected $fillable = [
        'cd_usu_perm,',
        'cd_arq_tp',
        'cd_usuario',
        'cd_emp',
        'fg_recebe_aviso',
        'fg_recebe_alerta',
        'fg_altera_status',
        'fg_exibir',
        'fg_entrada_manual',
        'fg_depara_manut',
        'fg_previsibilidade'
    ];

    public static function store($data)
    {   
        return self::firstOrCreate($data);
    }


}