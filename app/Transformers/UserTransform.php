<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\User;

class UserTransform extends TransformerAbstract
{
    protected $defaultIncludes = ['hotel'];

    public function transform(User $entity)
    {
        return [
            'id'        => $entity->id,
            'username'  => $entity->username,
            'name'      => $entity->name,
            'cpf'       => $entity->cpf
        ];
    }

    public function includeHotel(User $user)
    {
        return $this->item($user->hotel(), new HotelTransform);
    }

}
