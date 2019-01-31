<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Transformers\UserTransform;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Validators\ValidatesUserRequests;

class UserController extends Controller
{
    use ValidatesUserRequests;

    public function __construct()
    {
    }

    /**
     * @param int $id
     * @return
     *
     * @OA\Get(
     *     path="/user/{id}",
     *     tags={"User"},
     *     summary="get user data",
     *     description="Retorna os dados básicos de um usuário.",
     *     operationId="user/{id}",
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
        $user = User::find($id);
        if (!$user) {
            return response()->json(['error' => 'user not found'], 404);
        }
        return $this->response->item($user, new UserTransform());
    }

    public function store(Request $request, $rawData = false)
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
            'cpf' => $request->post('cpf')
        ]);
        
        if (!$user) {
            return response()->json(['error' => 'an error occurred while trying to create a user', 'error_list' => $user->getErrors()], 404);
        }
        
        return $rawData ? $user : $this->response->item($user, new UserTransform());
        
    }
}
