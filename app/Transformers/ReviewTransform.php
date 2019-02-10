<?php

namespace App\Transformers;

use App\Models\Review;
use League\Fractal\TransformerAbstract;

class ReviewTransform extends TransformerAbstract
{
    protected $defaultIncludes = ['hotel', 'platform'];

    public function transform(Review $entity)
    {
        return [
            'id' => $entity->id,
            'positive_description' => $entity->positive_description,
            'negative_description' => $entity->negative_description,
            'score' => round($entity->score, 1),
            'evaluation' => $entity->evaluation,
            'title' => $entity->title,
            'language' => $entity->language,
        ];
    }

    public function includeHotel(Review $review)
    {
        return $this->item($review->hotel(), new HotelTransform);
    }

    public function includePlatform(Review $review)
    {
        return $this->item($review->platform(), new PlatformTransform());
    }

}
