<?php

namespace App\Models;

use Dingo\Api\Auth\Auth;
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

    protected $errors = [];

    protected $fillable = [
        'user_id',
        'name',
        'username',
        'email',
        'password'
    ];

    public function store($data)
    {
        try {
            $user = self::firstOrNew(['username' => $data['username']]);
            if (!empty($user->cd_usuario)) {
                throw new \Exception('user exists');
            }
            // TODO: voltar a usar o create(). Fui vencido pelo Oracle...
            $user_fields = [
                'username'            => $data['email'],
                'name'       => $data['name'],
                'email'              => $data['email']
            ];
            $user = (new User())->create($user_fields);
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
        if (!isset($credentials['password']) || !isset($credentials['email'])) {
            return false;
        }

        $user = User::where('username', $credentials['email'])->where('password',encrypt($credentials['password']))
            ->first();

        if ($user) {
            Auth::login($user);
        }

        return $user;
    }

}