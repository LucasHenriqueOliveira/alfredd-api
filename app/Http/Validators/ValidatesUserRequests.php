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
            'password'      => 'required|string|min:8',
            'cpf'           => 'required|digits:11',
            'profile_id'    => 'required|exists:profiles,id',
            'hotel_id'      => 'required|exists:hotels,id',
        ]);
    }

    protected function validateUpdate(Request $request)
    {
        $this->validate($request, [
            'id'            => 'required|exists:users,id',
            'name'          => 'required|string|min:3',
            'password'      => 'required|string|min:8',
            'cpf'           => 'required|digits:11',
            'profile_id'    => 'required|exists:profiles,id',
            'hotel_id'      => 'required|exists:hotels,id',
        ]);
    }

}