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
    protected $errors = [];
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'description',
        'review_id'
    ];

    public function review() {
        return $this->hasOne(Review::class)->first();
    }

    public function store($data) {
        try {
            $answer = self::firstOrNew(['description' => $data['description']]);
            if (!empty($answer->id)) {
                throw new \Exception('answer exists');
            }
            $answer_fields = [
                'description' => $data['description'],
                'review_id'   => $data['review_id']
            ];
            $answer = self::create($answer_fields);
        } catch (\Exception $e) {
            $this->errors[] = $e->getMessage();
            return $e->getMessage();
        }
        return $answer;
    }

    public function put($data)
    {
        try {
            $answer = self::find($data['id']);
            if (empty($answer->id)) {
                throw new \Exception('answer not found');
            }
            $answer_fields = [
                'id'            => $data['id'],
                'description'   => $data['description'],
                'review_id'   => $data['review_id']
            ];
            $answer->update($answer_fields);
        } catch (\Exception $e) {
            $this->errors[] = $e->getMessage();
            return $e->getMessage();
        }
        return $answer;
    }

    public function del($id) {
        try {
            $answer = self::find($id);
            if (empty($answer->id)) {
                throw new \Exception('answer not found');
            }
            $answer->delete();
        } catch (\Exception $e) {
            $this->errors[] = $e->getMessage();
            return $e->getMessage();
        }
        return $answer;
    }

    public function getErrors()
    {
        return $this->errors;
    }
}