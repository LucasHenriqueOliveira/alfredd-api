<?php

namespace App\Models;

use App\Models\SupportClasses\UserParameters;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Laravel\Lumen\Auth\Authorizable;
use Traits\PropertiesTranslation;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Model implements AuthenticatableContract, AuthorizableContract, JWTSubject
{
    use Authenticatable, Authorizable, PropertiesTranslation;

    protected $table = 'cad_usu';
    protected $primaryKey = 'cd_usuario';
    const CREATED_AT = 'dt_cadastro';
    const UPDATED_AT = null;
    protected $errors = [];

    protected $fillable = [
        'cd_usuario',
        'cd_emp',
        'cd_idioma',
        'cd_formato_dt',
        'cd_formato_moeda',
        'cd_formato_separador',
        'cd_formato_hora',
        'cd_separador_milhar',
        'cd_role',
        'cd_formato_hr',
        'cd_permissao_relatorio',
        'cd_fuso_hr',
        'ds_usuario',
        'ds_nome_usuario',
        'ds_funcao_usuario',
        'ds_setor_usuario',
        'ds_email',
        'ds_email2',
        'ds_email3',
        'ds_pergunta',
        'ds_resposta',
        'fg_status',
        'fg_enviar_mensagem',
        'fg_login_atualizado',
        'fg_notif_resp_chamada',
        'fg_nao_notificar',
        'sg_separador_mil',
        'sg_tipo',
        'sg_separador_dec',
        'nu_telefone',
        'bl_rec_aviso_susp',
        'bl_rec_aviso_canc',
        'bl_rec_aviso_nova',
        'dt_cadastro',
    ];

    public function permissions()
    {
        return $this->hasMany('App\Models\Permission', 'cd_usuario', 'cd_usuario');
    }

    public function parameters()
    {
        return $this->hasMany(UserParameters::class, 'cd_usuario', 'cd_usuario');
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class, 'cad_usu_grupo', 'cd_usuario', 'cd_grupo', 'cd_usuario', 'cd_grupo');
    }

    public function activeParameters()
    {
        return UserParameters::where('cd_usuario', $this->cd_usuario)->where('fg_status', 'A')->first();
    }

    public function store($data)
    {
        try {
            $user = self::firstOrNew(['ds_usuario' => $data['username']]);
            if (!empty($user->cd_usuario)) {
                throw new \Exception('user exists');
            }
            // last id
            $last_user = User::where('cd_usuario','>',0)->orderBy('cd_usuario','desc')->first();
            $last_id = $last_user->cd_usuario + 1;
            // TODO: voltar a usar o create(). Fui vencido pelo Oracle...
            $user_fields = [
                'cd_usuario'            => $last_id,
                'cd_emp'                => $data['company_id'],
                'ds_usuario'            => $data['email'],
                'ds_nome_usuario'       => $data['name'],
                'ds_funcao_usuario'     => $data['function'],
                'ds_setor_usuario'      => $data['sector'],
                'ds_email'              => $data['email'],
                'fg_status'             => (!empty($data['is_active'])) ? 'L' : 'I',
                'cd_role'               => 1,
                'fg_login_atualizado'   => (!empty($data['is_active'])) ? 1 : 0,
            ];
            $user = (new User())->create($user_fields);
            // user parameters
            $last_user_par = UserParameters::where('cd_usu_par','>',0)->orderBy('cd_usu_par','desc')->first();
            $last_usu_par_id = $last_user_par->cd_usu_par + 1;
            $userParameters = (new UserParameters())->create([
                'cd_usu_par' => $last_usu_par_id,
                'cd_usuario' => $user->cd_usuario,
                'fg_status' => (!empty($data['is_active'])) ? 'A' : 'I',
            ]);
            $userParameters->password = $data['password'];
            $userParameters->save();
            // load full
            $user = self::find($user->cd_usuario);
        } catch (\Exception $e) {
            $this->errors[] = $e->getMessage();
            return $e->getMessage();
        }
        return $user;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function getPasswordAttribute()
    {
        $password = null;
        $userParameter = DB::table('cad_usu_par')
            ->where('cd_usuario', $this->cd_usuario)
            ->where('fg_status', 'A')
            ->first();
        if ($userParameter) {
            $password = $userParameter->ds_senha; // MD5 password
        }
        return $password;
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    private function attempt($credentials)
    {
        if (!isset($credentials['password']) or !isset($credentials['email'])) {
            return false;
        }

        $user = User::where('ds_nome', $credentials['email'])
            ->first();

        if ($user) {
            Auth::login($user);
        }

        return $user;
    }

}