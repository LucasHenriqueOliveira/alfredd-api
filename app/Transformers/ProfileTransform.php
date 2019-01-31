<?php

namespace App\Transformers;

use App\Models\Profile;
use League\Fractal\TransformerAbstract;

class ProfileTransform extends TransformerAbstract
{
    public function transform(Profile $entity)
    {
        return [
            'id'        => $entity->id,
            'name'      => $entity->name,
        ];
    }

}
