<?php

namespace App\Http\Validators;

use Illuminate\Http\Request;

trait ValidatesHotelRequests
{

    protected function validateNew(Request $request)
    {
        $this->validate($request, [
            'name'          => 'required|string|min:3'
        ]);
    }

    protected function validateUpdate(Request $request)
    {
        $this->validate($request, [
            'id'            => 'required|exists:hotels,id',
            'name'          => 'required|string|min:3'
        ]);
    }

}