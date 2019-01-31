<?php

namespace App\Models;

use Dingo\Api\Auth\Auth;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Laravel\Lumen\Auth\Authorizable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Model implements AuthenticatableContract, AuthorizableContract, JWTSubject
{
    use Authenticatable, Authorizable, SoftDeletes;

    protected $errors = [];
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'id',
        'name',
        'username',
        'password',
        'cpf',
        'profile_id',
        'hotel_id'
    ];

    public function profile() {
        return $this->hasOne(Profile::class,'id','profile_id')->first();
    }

    public function hotel() {
        return $this->hasOne(Hotel::class,'id','hotel_id')->first();
    }

    public function store($data)
    {
        try {
            $user = self::firstOrNew(['username' => $data['username']]);
            if (!empty($user->id)) {
                throw new \Exception('user exists');
            }
            $user_fields = [
                'name'      => $data['name'],
                'username'  => $data['username'],
                'password'  => Crypt::encrypt($data['password']),
                'cpf'       => $data['cpf'],
                'profile_id'=> $data['profile_id'],
                'hotel_id'  => $data['hotel_id']
            ];
            $user = self::create($user_fields);
        } catch (\Exception $e) {
            $this->errors[] = $e->getMessage();
            return $e->getMessage();
        }
        return $user;
    }

    public function put($data)
    {
        try {
            $user = self::find($data['id']);
            if (empty($user->id)) {
                throw new \Exception('user not found');
            }
            $user_fields = [
                'id'        => $data['id'],
                'name'      => $data['name'],
                'username'  => $data['username'],
                'password'  => Crypt::encrypt($data['password']),
                'cpf'       => $data['cpf'],
                'profile_id'=> $data['profile_id'],
                'hotel_id'  => $data['hotel_id']
            ];
            $user->update($user_fields);
        } catch (\Exception $e) {
            $this->errors[] = $e->getMessage();
            return $e->getMessage();
        }
        return $user;
    }

    public function del($id) {
        try {
            $user = self::find($id);
            if (empty($user->id)) {
                throw new \Exception('user not found');
            }
            $user->delete();
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