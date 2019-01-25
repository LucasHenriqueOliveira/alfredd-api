<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Dingo\Api\Routing\Helpers;
use App\Models\FileMsgCode;
use App\Models\FileTypeCode;

class Controller extends BaseController
{

    /**
     * @OA\Info(
     *   title="Alfredd API",
     *   version="1.0",
     *   description="API to Alfredd Service",
     *   @OA\Contact(
     *     email="dev@andrepiacentini.com.br",
     *     name="André M Piacentini"
     *   )
     * ),
     * @OA\Components(
     *     @OA\SecurityScheme(
     *          securityScheme="bearerToken",
     *          type="http",
     *          in="header",
     *          scheme="bearer",
     *          bearerFormat="JWT",
     *          name="Authorization"
     *     )
     * )
     */
    use Helpers;


    protected function getUserLogged()
    {
        return \Auth::user();
    }

}
