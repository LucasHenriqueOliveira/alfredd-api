<?php

namespace App\Http\Controllers;

use App\Http\Validators\ValidatesPlatformRequests;
use App\Models\Platform;
use App\Transformers\PlatformTransform;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PlatformController extends Controller
{
    use ValidatesPlatformRequests;

    public function __construct()
    {
    }

    /**
     * @param int $id
     * @return
     *
     * @OA\Get(
     *     path="/platform/{id}",
     *     tags={"Platform"},
     *     summary="get platform data",
     *     description="Retorna os dados básicos de uma plataforma.",
     *     operationId="platform/{id}",
     *     security={{"bearerToken":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID da plataforma",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid token"
     *     )
     * )
     */
    public function get($id)
    {
        if (Gate::denies('view', $this->getUserLogged())) {
            return response()->json(['error' => 'policy: cannot view platform data'], 403);
        }
        $platform = Platform::find($id);
        if (!$platform) {
            return response()->json(['error' => 'platform not found'], 404);
        }
        return $this->response->item($platform, new PlatformTransform());
    }

    public function getAll()
    {
        if (Gate::denies('view', $this->getUserLogged())) {
            return response()->json(['error' => 'policy: cannot view platform data'], 403);
        }
        $platforms = Platform::all();
        if (!$platforms) {
            return response()->json(['error' => 'plataform not found'], 404);
        }
        return $this->response->collection($platforms, new PlatformTransform());
    }

    public function post(Request $request, $rawData = false)
    {
        if (Gate::denies('create', $this->getUserLogged())) {
            return response()->json(['error' => 'policy: cannot create a new platform'], 403);
        }
        $this->validateNew($request);

        // create
        $platform = (new Platform())->store([
            'name' => $request->post('name'),
            'active' => $request->post('active')
        ]);
        
        if (!$platform) {
            return response()->json(['error' => 'an error occurred while trying to create a platform', 'error_list' => $platform->getErrors()], 404);
        }
        
        return $rawData ? $platform : $this->response->item($platform, new PlatformTransform());
    }

    public function put(Request $request, $id)
    {
        if (Gate::denies('update', $this->getUserLogged())) {
            return response()->json(['error' => 'policy: cannot update an existent platform'], 403);
        }
        $request['id'] = $id;
        $this->validateUpdate($request);

        // create
        $platform = (new Platform())->put([
            'id' => $id,
            'name' => $request->post('name'),
            'active' => $request->post('active')
        ]);

        if (!$platform) {
            return response()->json(['error' => 'an error occurred while trying to create a platform', 'error_list' => $platform->getErrors()], 404);
        }

        return $this->response->item($platform, new PlatformTransform());
    }

    public function delete($id)
    {
        if (Gate::denies('delete', $this->getUserLogged())) {
            return response()->json(['error' => 'policy: cannot delete an existent platform'], 403);
        }

        // create
        $platform = (new Platform())->del($id);

        if ((!$platform) || (is_string($platform))) {
            return response()->json(['error' => 'an error occurred while trying to delete a platform : ' . $platform], 404);
        }

        return $this->response->item($platform, new PlatformTransform());
    }
}
