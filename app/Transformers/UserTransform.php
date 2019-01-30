<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\User;

class UserTransformer extends TransformerAbstract
{
    public function transform(User $entity)
    {
        return [
            'id'        => $entity->id,
            'username'  => $entity->username,
            'name'      => $entity->name,
            'cpf'       => $entity->cpf
        ];
    }
}
