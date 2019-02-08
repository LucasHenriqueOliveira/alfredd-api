<?php

namespace App\Http\Validators;

use Illuminate\Http\Request;

trait ValidatesAnswerRequests
{

    protected function validateNew(Request $request)
    {
        $this->validate($request, [
            'description'          => 'required|string|min:3',
            'review_id'           => 'required|exists:reviews,id'
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