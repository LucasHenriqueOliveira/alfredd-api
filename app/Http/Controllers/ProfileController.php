<?php

namespace App\Http\Controllers;

use App\Http\Validators\ValidatesProfileRequests;
use App\Models\Profile;
use App\Models\User;
use App\Transformers\ProfileTransform;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ProfileController extends Controller
{
    use ValidatesProfileRequests;

    public function __construct()
    {
    }

    /**
     * @param int $id
     * @return
     *
     * @OA\Get(
     *     path="/profile/{id}",
     *     tags={"Profile"},
     *     summary="get profile data",
     *     description="Retorna os dados básicos de um perfil.",
     *     operationId="profile/{id}",
     *     security={{"bearerToken":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do perfil",
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
            return response()->json(['error' => 'policy: cannot view profile data'], 403);
        }
        $profile = Profile::find($id);
        if (!$profile) {
            return response()->json(['error' => 'profile not found'], 404);
        }
        return $this->response->item($profile, new ProfileTransform());
    }

    public function post(Request $request, $rawData = false)
    {

        if (Gate::denies('create', $this->getUserLogged())) {
            return response()->json(['error' => 'policy: cannot create a new profile'], 403);
        }
        $this->validateNew($request);

        // create
        $profile = (new Profile())->store([
            'name' => $request->post('name')
        ]);
        
        if (!$profile) {
            return response()->json(['error' => 'an error occurred while trying to create a profile', 'error_list' => $profile->getErrors()], 404);
        }
        
        return $rawData ? $profile : $this->response->item($profile, new ProfileTransform());
        
    }

    public function put(Request $request, $id)
    {

        if (Gate::denies('update', $this->getUserLogged())) {
            return response()->json(['error' => 'policy: cannot update an existent profile'], 403);
        }
        $request['id'] = $id;
        $this->validateUpdate($request);

        // create
        $profile = (new Profile())->put([
            'id' => $id,
            'name' => $request->post('name')
        ]);

        if (!$profile) {
            return response()->json(['error' => 'an error occurred while trying to create a profile', 'error_list' => $profile->getErrors()], 404);
        }

        return $this->response->item($profile, new ProfileTransform());

    }

    public function delete($id)
    {

        if (Gate::denies('delete', $this->getUserLogged())) {
            return response()->json(['error' => 'policy: cannot delete an existent profile'], 403);
        }

        // create
        $profile = (new Profile())->del($id);

        if (!$profile) {
            return response()->json(['error' => 'an error occurred while trying to create a profile'], 404);
        }

        return $this->response->item($profile, new ProfileTransform());

    }
}
