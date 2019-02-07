<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    protected $errors = [];
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'name',
        'active'
    ];

    public function store($data) {
        try {
            $hotel = self::firstOrNew(['name' => $data['name']]);
            if (!empty($hotel->id)) {
                throw new \Exception('hotel exists');
            }
            $hotel_fields = [
                'name'      => $data['name'],
                'active'      => $data['active']
            ];
            $hotel = self::create($hotel_fields);
        } catch (\Exception $e) {
            $this->errors[] = $e->getMessage();
            return $e->getMessage();
        }
        return $hotel;
    }

    public function put($data)
    {
        try {
            $hotel = self::find($data['id']);
            if (empty($hotel->id)) {
                throw new \Exception('hotel not found');
            }
            $hotel_fields = [
                'id'        => $data['id'],
                'name'      => $data['name'],
                'active'    => $data['active']
            ];
            $hotel->update($hotel_fields);
        } catch (\Exception $e) {
            $this->errors[] = $e->getMessage();
            return $e->getMessage();
        }
        return $hotel;
    }

    public function del($id) {
        try {
            $hotel = self::find($id);
            if (empty($hotel->id)) {
                throw new \Exception('hotel not found');
            }
            $hotel->delete();
        } catch (\Exception $e) {
            $this->errors[] = $e->getMessage();
            return $e->getMessage();
        }
        return $hotel;
    }

    public function getErrors()
    {
        return $this->errors;
    }

}