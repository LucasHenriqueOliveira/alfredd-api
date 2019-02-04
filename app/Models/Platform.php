<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Platform extends Model
{
    protected $errors = [];
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'name',
        'active'
    ];


    public function store($data) {
        try {
            $platform = self::firstOrNew(['name' => $data['name']]);
            if (!empty($platform->id)) {
                throw new \Exception('platform exists');
            }
            $platform_fields = [
                'name'      => $data['name'],
                'active'      => $data['active']
            ];
            $platform = self::create($platform_fields);
        } catch (\Exception $e) {
            $this->errors[] = $e->getMessage();
            return $e->getMessage();
        }
        return $platform;
    }

    public function put($data)
    {
        try {
            $platform = self::find($data['id']);
            if (empty($platform->id)) {
                throw new \Exception('platform not found');
            }
            $platform_fields = [
                'id'        => $data['id'],
                'name'      => $data['name'],
                'active'    => $data['active']
            ];
            $platform->update($platform_fields);
        } catch (\Exception $e) {
            $this->errors[] = $e->getMessage();
            return $e->getMessage();
        }
        return $platform;
    }

    public function del($id) {
        try {
            $platform = self::find($id);
            if (empty($platform->id)) {
                throw new \Exception('platform not found');
            }
            $platform->delete();
        } catch (\Exception $e) {
            $this->errors[] = $e->getMessage();
            return $e->getMessage();
        }
        return $platform;
    }

    public function getErrors()
    {
        return $this->errors;
    }

}