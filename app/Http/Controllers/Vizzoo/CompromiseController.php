<?php

namespace App\Http\Controllers\Vizzoo;

use App\Http\Controllers\CompromiseController;
use App\Models\Compromise;
use Illuminate\Http\Request;
use App\Transformers\CompromiseTransformer;

class VizzooCompromiseController extends CompromiseController
{

    public function get($uniqueCode)
    {
        $compromise = Compromise::findCompromise($uniqueCode, 'cd_ident_unico');
        if (!$compromise) {
            return $this->response->error('compromise not found.', 404);
        }
        return $this->response->collection($compromise, new CompromiseTransformer);
    }

    public function update($uniqueCode, Request $request, $field = 'cd_ident_unico')
    {
        return parent::update($uniqueCode, $request, $field);
    }    

}