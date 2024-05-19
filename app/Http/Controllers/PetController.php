<?php

namespace App\Http\Controllers;

use App\Http\Requests\FindByStatusRequest;
use App\Http\Requests\PetStoreRequest;
use App\Http\Requests\UpdateRequest;
use App\Http\Requests\UploadImageRequest;
use App\Http\Services\PetService;
use App\Http\Transformers\PetTransformer;
use Illuminate\Http\JsonResponse;

class PetController extends Controller
{
    /**
     * Find pets by status
     *
     * @param FindByStatusRequest $request
     *
     * @return JsonResponse
     */
    public function index(FindByStatusRequest $request): JsonResponse {

        [$pets, $nextCursor] = app(PetService::class)->findPetsByStatuses($request);

        $data = [
            'data' => [],
            'nextCursor' => $nextCursor,
        ];

        foreach ($pets as $pet) {

            $data['data'][] = PetTransformer::transform($pet);
        }

        return response()->json($data);
    }

    /**
     * Get pet by id
     *
     * @param int $petId
     *
     * @return JsonResponse
     */
    public function show (int $petId): JsonResponse {

        $pet = app(PetService::class)->getPetById($petId);

        $pet->load(['category', 'tags']);

        return response()->json(PetTransformer::transform($pet));
    }

    /**
     * Add new pet to the store
     *
     * @param PetStoreRequest $request
     *
     * @return JsonResponse
     */
    public function store (PetStoreRequest $request): JsonResponse {

        $pet = app(PetService::class)->store($request);

        return response()->json(PetTransformer::transform($pet), 201);
    }

    /**
     * Upload an image to the pet
     *
     * @param UploadImageRequest $request
     * @param int $petId
     *
     * @return JsonResponse
     */
    public function uploadImage (UploadImageRequest $request, int $petId): JsonResponse {

        $pet = app(PetService::class)->uploadImage($request, $petId);

        $pet->load(['category', 'tags']);

        return response()->json(PetTransformer::transform($pet));
    }

    /**
     * Pet update
     *
     * @param UpdateRequest $request
     * @param int $petId
     *
     * @return JsonResponse
     */
    public function update (UpdateRequest $request,  int $petId): JsonResponse {

        $pet = app(PetService::class)->update($request, $petId);

        $pet->load(['category', 'tags']);

        return response()->json(PetTransformer::transform($pet));
    }

    /**
     * Delete pet by id
     *
     * @param int $id
     *
     * @return JsonResponse
     */
    public function destroy (int $id): JsonResponse {

        app(PetService::class)->destroy($id);

        return response()->json(null, 204);
    }
}
