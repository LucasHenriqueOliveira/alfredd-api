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
        'language',
        'hotel_id',
        'platform_id'
    ];

    public function hotel() {
        return $this->hasOne(Hotel::class,'id', 'hotel_id')->first();
    }

    public function platform() {
        return $this->hasOne(Platform::class, 'id', 'platform_id')->first();
    }


    public function store($data) {
        try {
            $review = self::firstOrNew(['title' => $data['title']]);
            if (!empty($review->id)) {
                throw new \Exception('review exists with this title');
            }
            $review_fields = [
                'positive_description' => $data['positive_description'],
                'negative_description' => $data['negative_description'],
                'score' => $data['score'],
                'evaluation' => $data['evaluation'],
                'title' => $data['title'],
                'language' => $data['language'],
                'hotel_id' => $data['hotel_id'],
                'platform_id' => $data['platform_id'],
            ];
            $review = self::create($review_fields);
        } catch (\Exception $e) {
            $this->errors[] = $e->getMessage();
            return $e->getMessage();
        }
        return $review;
    }

    public function put($data)
    {
        try {
            $review = self::find($data['id']);
            if (empty($review->id)) {
                throw new \Exception('review not found');
            }
            $review_fields = [
                'id'            => $data['id'],
                'positive_description' => $data['positive_description'],
                'negative_description' => $data['negative_description'],
                'score' => $data['score'],
                'evaluation' => $data['evaluation'],
                'title' => $data['title'],
                'language' => $data['language'],
                'hotel_id' => $data['hotel_id'],
                'platform_id' => $data['platform_id'],
            ];
            $review->update($review_fields);
        } catch (\Exception $e) {
            $this->errors[] = $e->getMessage();
            return $e->getMessage();
        }
        return $review;
    }

    public function del($id) {
        try {
            $review = self::find($id);
            if (empty($review->id)) {
                throw new \Exception('review not found');
            }
            $review->delete();
        } catch (\Exception $e) {
            $this->errors[] = $e->getMessage();
            return $e->getMessage();
        }
        return $review;
    }

    public function getErrors()
    {
        return $this->errors;
    }
}