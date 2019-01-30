<?php

namespace App\Transformers;

use App\Models\Hotel;
use League\Fractal\TransformerAbstract;

class HotelTransform extends TransformerAbstract
{
    public function transform(Hotel $entity)
    {
        return [
            'id'        => $entity->id,
            'name'      => $entity->name,
        ];
    }

}
