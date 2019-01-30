<?php
/**
 * Created by PhpStorm.
 * User: piacentini
 * Date: 30/01/19
 * Time: 17:49
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    protected $fillable = [
        'description'
    ];

    public function review() {
        return $this->hasOne(Review::class)->first();
    }
}