<?php
namespace App\Http\Controllers;

use Tymon\JWTAuth\Claims\Collection;
use Tymon\JWTAuth\JWTAuth;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Models\User;
use Tymon\JWTAuth\Payload;

/**
 * Class AuthenticationController
 * @package App\Http\Controllers
 */
class AuthenticationController extends Controller
{

    /**
     * @var JWTAuth
     */
    private $auth;

    /**
     * @param JWTAuth $auth
     */
    public function __construct(JWTAuth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @OA\Post(
     *     path="/auth/authorize",
     *     tags={"Security"},
     *     summary="Authenticate",
     *     description="Retorna um JWT Token com validade limitada de tempo",
     *     operationId="auth/authenticate",
     *     @OA\RequestBody(
     *         description="dados do usuário de login",
     *         required=true,
     *         @OA\JsonContent(
     *              title="credentials",
     *              type="object",
     *              @OA\Property(property="username", type="string"),
     *              @OA\Property(property="password", type="string"),
     *              example="{""username"":""joe"",""password"":""secret""}"
     *        )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="token", type="string"),
     *             example="{""token"":""BIG TOKEN""}"
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid username/password supplied"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="You must inform user and password parameters"
     *     )
     * )
     */
    public function authenticate(Request $request)
    {
        $token = false;
        // grab credentials from the request
        $credentials = $request->only('username', 'password');
        if ((empty($credentials['username'])) || (empty($credentials['password']))) return response()->json(['error' => 'username and password are requested'],401);
        // encrpypt
        $credentials['username'] = urldecode($credentials['username']);  // TODO: isso tá feio
        $credentials['password'] = md5(urldecode($credentials['password']));  // TODO: bcrypt()
        try {
            // attempt to verify the credentials and create a token for the user
            $user = User::where('ds_usuario', $credentials['username'])->first();
            if (!$user) {
                return response()->json(['error' => 'user not found'], 500);
            }
            if ($user->password===$credentials['password']) {
                $token = $this->auth->fromUser($user);
            }
            if (!$token) {
                return response()->json(['error' => 'invalid credentials'], 401);
            }
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json(['error' => 'could not create token'], 500);
        }
        // all good so return the token
        return response()->json(compact('token'));
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @OA\Get(
     *     path="/auth/logout",
     *     tags={"Security"},
     *     summary="Logout and destroy JWT token",
     *     description="Encerra as permissões estabelecidas no token informado.",
     *     operationId="auth/logout",
     *     security={{"bearerToken":{}}},
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
    public function logout()
    {
        $this->auth->invalidate(true);
        return response()->json(['message' => 'success']);
    }


    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @OA\Get(
     *     path="/auth/refresh",
     *     tags={"Security"},
     *     summary="Refresh JWT token",
     *     description="Renova o JWT token, se possível.",
     *     operationId="auth/refresh",
     *     security={{"bearerToken":{}}},
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
    public function refresh()
    {
        if (!$this->auth->check()) {
            throw new \Symfony\Component\HttpKernel\Exception\NotAcceptableHttpException('token not acceptable');
        }
        $this->auth->refresh();
        return response()->json(['message' => 'token refreshed']);
    }


    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @OA\Get(
     *     path="/auth/payload",
     *     tags={"Security"},
     *     summary="returns JWT payload data",
     *     description="Retorna os parâmetros do token JWT.",
     *     operationId="auth/payload",
     *     security={{"bearerToken":{}}},
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
    public function payload()
    {
        if (!$this->auth->check()) {
            throw new \Symfony\Component\HttpKernel\Exception\NotAcceptableHttpException('token not acceptable');
        }
        return response()->json(['payload' => $this->auth->payload()->toArray()]);
    }
}