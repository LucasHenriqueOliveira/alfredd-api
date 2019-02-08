<?php

namespace App\Transformers;

use App\Models\Answer;
use League\Fractal\TransformerAbstract;

class AnswerTransform extends TransformerAbstract
{
    public function transform(Answer $entity)
    {
        return [
            'id'            => $entity->id,
            'description'   => $entity->description,
        ];
    }

}
