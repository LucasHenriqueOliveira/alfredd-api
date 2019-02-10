<?php

namespace App\Http\Validators;

use Illuminate\Http\Request;

trait ValidatesReviewRequests
{

    protected function validateNew(Request $request)
    {
        $this->validate($request, [
            'positive_description' => 'required|string|min:10',
            'negative_description' => 'required|string|min:10',
            'score' => 'required|float',
            'evaluation' => 'required|string|max:45',
            'title' => 'required|string|max:45',
            'hotel_id'             => 'required|exists:hotels,id',
            'platform_id'          => 'required|exists:platforms,id'
        ]);
    }

    protected function validateUpdate(Request $request)
    {
        $this->validate($request, [
            'id'            => 'required|exists:answers,id',
            'description'   => 'required|string|min:3',
            'review_id'           => 'required|exists:reviews,id'
        ]);
    }

}