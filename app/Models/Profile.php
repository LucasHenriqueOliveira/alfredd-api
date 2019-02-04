<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $errors = [];
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'name'
    ];

    public function store($data) {
        try {
            $profile = self::firstOrNew(['name' => $data['name']]);
            if (!empty($profile->id)) {
                throw new \Exception('profile exists');
            }
            $profile_fields = [
                'name'      => $data['name']
            ];
            $profile = self::create($profile_fields);
        } catch (\Exception $e) {
            $this->errors[] = $e->getMessage();
            return $e->getMessage();
        }
        return $profile;
    }

    public function put($data)
    {
        try {
            $profile = self::find($data['id']);
            if (empty($profile->id)) {
                throw new \Exception('profile not found');
            }
            $profile_fields = [
                'id'        => $data['id'],
                'name'      => $data['name']
            ];
            $profile->update($profile_fields);
        } catch (\Exception $e) {
            $this->errors[] = $e->getMessage();
            return $e->getMessage();
        }
        return $profile;
    }

    public function del($id) {
        try {
            $profile = self::find($id);
            if (empty($profile->id)) {
                throw new \Exception('profile not found');
            }
            $profile->delete();
        } catch (\Exception $e) {
            $this->errors[] = $e->getMessage();
            return $e->getMessage();
        }
        return $profile;
    }

    public function getErrors()
    {
        return $this->errors;
    }
}