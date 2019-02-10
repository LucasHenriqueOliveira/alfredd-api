<?php

namespace App\Http\Controllers;

use App\Http\Validators\ValidatesAnswerRequests;
use App\Models\Review;
use App\Transformers\ReviewTransform;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ReviewController extends Controller
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
     *     path="/review/{id}",
     *     tags={"Profile"},
     *     summary="get review data",
     *     description="Retorna os dados básicos de um perfil.",
     *     operationId="review/{id}",
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
            return response()->json(['error' => 'policy: cannot view review data'], 403);
        }
        $review = Review::find($id);
        if (!$review) {
            return response()->json(['error' => 'review not found'], 404);
        }
        return $this->response->item($review, new ReviewTransform());
    }

    public function getAll()
    {
        if (Gate::denies('view', $this->getUserLogged())) {
            return response()->json(['error' => 'policy: cannot view review data'], 403);
        }
        $reviews = Review::all();
        if (!$reviews) {
            return response()->json(['error' => 'review not found'], 404);
        }
        return $this->response->collection($reviews, new ReviewTransform());
    }

    public function post(Request $request, $rawData = false)
    {

        if (Gate::denies('create', $this->getUserLogged())) {
            return response()->json(['error' => 'policy: cannot create a new review'], 403);
        }
        $this->validateNew($request);

        // create
        $review = (new Review())->store([
            'positive_description' => $request->post('positive_description'),
            'negative_description' => $request->post('negaitve_description'),
            'score' => $request->post('score'),
            'evaluation' => $request->post('evaluation'),
            'title' => $request->post('title'),
            'language' => $request->post('language'),
            'hotel_id' => $request->post('review_id'),
            'platform_id' => $request->post('platform_id')
        ]);
        
        if ( (!$review) || (is_string($review)) ) {
            return response()->json(['error' => 'an error occurred while trying to create a review', 'error_list' => $review], 404);
        }
        
        return $rawData ? $review : $this->response->item($review, new ReviewTransform());
        
    }

    public function put(Request $request, $id)
    {

        if (Gate::denies('update', $this->getUserLogged())) {
            return response()->json(['error' => 'policy: cannot update an existent review'], 403);
        }
        $request['id'] = $id;
        $this->validateUpdate($request);

        // create
        $review = (new Review())->put([
            'id' => $id,
            'positive_description' => $request->post('positive_description'),
            'negative_description' => $request->post('negaitve_description'),
            'score' => $request->post('score'),
            'evaluation' => $request->post('evaluation'),
            'title' => $request->post('title'),
            'language' => $request->post('language'),
            'hotel_id' => $request->post('review_id'),
            'platform_id' => $request->post('platform_id')
        ]);

        if (!$review) {
            return response()->json(['error' => 'an error occurred while trying to create a review', 'error_list' => $review->getErrors()], 404);
        }

        return $this->response->item($review, new ReviewTransform());

    }

    public function delete($id)
    {

        if (Gate::denies('delete', $this->getUserLogged())) {
            return response()->json(['error' => 'policy: cannot delete an existent review'], 403);
        }

        // create
        $review = (new Review())->del($id);

        if (!$review) {
            return response()->json(['error' => 'an error occurred while trying to create a review'], 404);
        }

        return $this->response->item($review, new ReviewTransform());

    }
}
