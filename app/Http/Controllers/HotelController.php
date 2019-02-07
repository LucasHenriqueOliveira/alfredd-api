<?php

namespace App\Http\Controllers;

use App\Http\Validators\ValidatesHotelRequests;
use App\Models\Hotel;
use App\Transformers\HotelTransform;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class HotelController extends Controller
{
    use ValidatesHotelRequests;

    public function __construct()
    {
    }

    /**
     * @param int $id
     * @return
     *
     * @OA\Get(
     *     path="/hotel/{id}",
     *     tags={"Hotel"},
     *     summary="get hotel data",
     *     description="Retorna os dados básicos de um hotel.",
     *     operationId="hotel/{id}",
     *     security={{"bearerToken":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do hotel",
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
            return response()->json(['error' => 'policy: cannot view hotel data'], 403);
        }
        $hotel = Hotel::find($id);
        if (!$hotel) {
            return response()->json(['error' => 'hotel not found'], 404);
        }
        return $this->response->item($hotel, new HotelTransform());
    }

    public function getAll()
    {
        if (Gate::denies('view', $this->getUserLogged())) {
            return response()->json(['error' => 'policy: cannot view hotel data'], 403);
        }
        $hotels = Hotel::all();
        if (!$hotels) {
            return response()->json(['error' => 'plataform not found'], 404);
        }
        return $this->response->collection($hotels, new HotelTransform());
    }

    public function post(Request $request, $rawData = false)
    {
        if (Gate::denies('create', $this->getUserLogged())) {
            return response()->json(['error' => 'policy: cannot create a new hotel'], 403);
        }
        $this->validateNew($request);

        // create
        $hotel = (new Hotel())->store([
            'name' => $request->post('name'),
            'active' => $request->post('active')
        ]);
        
        if (!$hotel) {
            return response()->json(['error' => 'an error occurred while trying to create a hotel', 'error_list' => $hotel->getErrors()], 404);
        }
        
        return $rawData ? $hotel : $this->response->item($hotel, new HotelTransform());
    }

    public function put(Request $request, $id)
    {
        if (Gate::denies('update', $this->getUserLogged())) {
            return response()->json(['error' => 'policy: cannot update an existent hotel'], 403);
        }
        $request['id'] = $id;
        $this->validateUpdate($request);

        // create
        $hotel = (new Hotel())->put([
            'id' => $id,
            'name' => $request->post('name'),
            'active' => $request->post('active')
        ]);

        if (!$hotel) {
            return response()->json(['error' => 'an error occurred while trying to create a hotel', 'error_list' => $hotel->getErrors()], 404);
        }

        return $this->response->item($hotel, new HotelTransform());
    }

    public function delete($id)
    {
        if (Gate::denies('delete', $this->getUserLogged())) {
            return response()->json(['error' => 'policy: cannot delete an existent hotel'], 403);
        }

        // create
        $hotel = (new Hotel())->del($id);

        if ((!$hotel) || (is_string($hotel))) {
            return response()->json(['error' => 'an error occurred while trying to delete a hotel : ' . $hotel], 404);
        }

        return $this->response->item($hotel, new HotelTransform());
    }
}
