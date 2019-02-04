<?php

namespace App\Transformers;

use App\Models\Platform;
use League\Fractal\TransformerAbstract;

class PlatformTransform extends TransformerAbstract
{
    public function transform(Platform $entity)
    {
        return [
            'id'        => $entity->id,
            'name'      => $entity->name,
        ];
    }

}
