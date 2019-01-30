<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'positive_description',
        'negative_description',
        'score',
        'evaluation',
        'title',
        'language'
    ];

    public function hotel() {
        return $this->hasOne(Hotel::class)->first();
    }

    public function platform() {
        return $this->hasOne(Platform::class)->first();
    }
}