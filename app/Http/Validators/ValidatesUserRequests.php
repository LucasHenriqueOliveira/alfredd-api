<?php

namespace App\Http\Validators;

use Illuminate\Http\Request;

trait ValidatesUserRequests
{

    protected function validateNew(Request $request)
    {
        $this->validate($request, [
            'name'          => 'required|string|min:3',
            'username'      => 'required|email|unique:users,username',
            'password'      => 'required|string|min:8'
        ]);
    }

}