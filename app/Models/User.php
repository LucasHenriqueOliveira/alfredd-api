<?php

namespace App\Models;

use Dingo\Api\Auth\Auth;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Laravel\Lumen\Auth\Authorizable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Model implements AuthenticatableContract, AuthorizableContract, JWTSubject
{
    use Authenticatable, Authorizable;

    protected $errors = [];

    protected $fillable = [
        'id',
        'name',
        'username',
        'password',
        'cpf'
    ];

    public function store($data)
    {
        try {
            $user = self::firstOrNew(['username' => $data['username']]);
            if (!empty($user->cd_usuario)) {
                throw new \Exception('user exists');
            }
            $user_fields = [
                'name'      => $data['name'],
                'username'  => $data['username'],
                'password'  => Crypt::encrypt($data['password']),
            ];
            $user = self::create($user_fields);
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

    public static function attempt($credentials)
    {
        if (!isset($credentials['password']) || !isset($credentials['username'])) {
            return false;
        }
        $user = self::where('username', $credentials['username'])->first();

        // check password
        if ($user) {
            if ((Hash::check($credentials['password'],$user->password))==false) $user = false;
        }

        return $user;
    }

}