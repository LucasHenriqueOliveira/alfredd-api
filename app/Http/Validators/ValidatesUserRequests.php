<?php

namespace App\Http\Validators;

use Illuminate\Http\Request;

trait ValidatesUserRequests
{

    protected function validateNew(Request $request)
    {
        $this->validate($request, [
            'name'          => 'required|string|min:3',
            'username'      => 'required|email|unique:cad_usu,ds_usuario',            
            'password'      => 'required|string|min:8',
            'email'         => 'required|email|unique:cad_usu,ds_email',
            'company_doc'   => 'required|numeric|digits:14|exists:cad_emp_fl,nu_cnpj'
        ]);
    }

}