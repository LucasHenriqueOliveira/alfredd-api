<?php

namespace App\Http\Controllers;

use App\Http\Validators\ValidatesProfileRequests;
use App\Models\Profile;
use App\Models\User;
use App\Transformers\UserTransform;
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
     *     description="Retorna os dados básicos de um usuário.",
     *     operationId="profile/{id}",
     *     security={{"bearerToken":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do usuário",
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
            return response()->json(['error' => 'policy: cannot view user data'], 403);
        }
        $profile = Profile::find($id);
        if (!$profile) {
            return response()->json(['error' => 'user not found'], 404);
        }
        return $this->response->item($profile, new UserTransform());
    }

    public function post(Request $request, $rawData = false)
    {

        if (Gate::denies('create', $this->getUserLogged())) {
            return response()->json(['error' => 'policy: cannot create a new user'], 403);
        }
        $this->validateNew($request);

        // create
        $user = (new User())->store([
            'name' => $request->post('name'),
            'username' => $request->post('username'),
            'password' => $request->post('password'),
            'cpf' => $request->post('cpf'),
            'profile_id' => $request->post('profile_id'),
            'hotel_id' => $request->post('hotel_id'),
        ]);
        
        if (!$user) {
            return response()->json(['error' => 'an error occurred while trying to create a user', 'error_list' => $user->getErrors()], 404);
        }
        
        return $rawData ? $user : $this->response->item($user, new UserTransform());
        
    }

    public function put(Request $request, $id)
    {

        if (Gate::denies('update', $this->getUserLogged())) {
            return response()->json(['error' => 'policy: cannot update an existent user'], 403);
        }
        $request['id'] = $id;
        $this->validateUpdate($request);

        // create
        $user = (new User())->put([
            'id' => $id,
            'name' => $request->post('name'),
            'username' => $request->post('username'),
            'password' => $request->post('password'),
            'cpf' => $request->post('cpf'),
            'profile_id' => $request->post('profile_id'),
            'hotel_id' => $request->post('hotel_id'),
        ]);

        if (!$user) {
            return response()->json(['error' => 'an error occurred while trying to create a user', 'error_list' => $user->getErrors()], 404);
        }

        return $this->response->item($user, new UserTransform());

    }

    public function delete($id)
    {

        if (Gate::denies('delete', $this->getUserLogged())) {
            return response()->json(['error' => 'policy: cannot delete an existent user'], 403);
        }

        // create
        $user = (new User())->del($id);

        if (!$user) {
            return response()->json(['error' => 'an error occurred while trying to create a user', 'error_list' => $user->getErrors()], 404);
        }

        return $this->response->item($user, new UserTransform());

    }
}
