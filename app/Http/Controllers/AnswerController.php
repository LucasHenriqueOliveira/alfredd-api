<?php

namespace App\Http\Controllers;

use App\Http\Validators\ValidatesAnswerRequests;
use App\Models\Answer;
use App\Transformers\AnswerTransform;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AnswerController extends Controller
{
    use ValidatesAnswerRequests;

    public function __construct()
    {
    }

    /**
     * @param int $id
     * @return
     *
     * @OA\Get(
     *     path="/answer/{id}",
     *     tags={"Profile"},
     *     summary="get answer data",
     *     description="Retorna os dados básicos de um perfil.",
     *     operationId="answer/{id}",
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
            return response()->json(['error' => 'policy: cannot view answer data'], 403);
        }
        $answer = Answer::find($id);
        if (!$answer) {
            return response()->json(['error' => 'answer not found'], 404);
        }
        return $this->response->item($answer, new AnswerTransform());
    }

    public function getAll()
    {
        if (Gate::denies('view', $this->getUserLogged())) {
            return response()->json(['error' => 'policy: cannot view answer data'], 403);
        }
        $answers = Answer::all();
        if (!$answers) {
            return response()->json(['error' => 'answer not found'], 404);
        }
        return $this->response->collection($answers, new AnswerTransform());
    }

    public function post(Request $request, $rawData = false)
    {

        if (Gate::denies('create', $this->getUserLogged())) {
            return response()->json(['error' => 'policy: cannot create a new answer'], 403);
        }
        $this->validateNew($request);

        // create
        $answer = (new Answer())->store([
            'description' => $request->post('description'),
            'review_id' => $request->post('review_id')
        ]);
        
        if ( (!$answer) || (is_string($answer)) ) {
            return response()->json(['error' => 'an error occurred while trying to create a answer', 'error_list' => $answer], 404);
        }
        
        return $rawData ? $answer : $this->response->item($answer, new AnswerTransform());
        
    }

    public function put(Request $request, $id)
    {

        if (Gate::denies('update', $this->getUserLogged())) {
            return response()->json(['error' => 'policy: cannot update an existent answer'], 403);
        }
        $request['id'] = $id;
        $this->validateUpdate($request);

        // create
        $answer = (new Answer())->put([
            'id' => $id,
            'description' => $request->post('description'),
            'review_id' => $request->post('review_id'),
        ]);

        if (!$answer) {
            return response()->json(['error' => 'an error occurred while trying to create a answer', 'error_list' => $answer->getErrors()], 404);
        }

        return $this->response->item($answer, new AnswerTransform());

    }

    public function delete($id)
    {

        if (Gate::denies('delete', $this->getUserLogged())) {
            return response()->json(['error' => 'policy: cannot delete an existent answer'], 403);
        }

        // create
        $answer = (new Answer())->del($id);

        if (!$answer) {
            return response()->json(['error' => 'an error occurred while trying to create a answer'], 404);
        }

        return $this->response->item($answer, new AnswerTransform());

    }
}
