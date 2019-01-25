<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\User;

class UserTransformer extends TransformerAbstract
{
    public function transform(User $entity)
    {
        return [
            'user_id'       => $entity->user_id,
            'company_id'    => $entity->company_id,
            'username'      => $entity->username,
            'fullname'      => $entity->user_fullname,
            'email'         => $entity->ds_email
        ];
    }
}
