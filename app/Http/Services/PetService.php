<?php

namespace App\Http\Services;

use App\Http\Repositories\PetRepository;
use App\Http\Requests\FindByStatusRequest;
use App\Http\Requests\PetStoreRequest;
use App\Http\Requests\UpdateRequest;
use App\Http\Requests\UploadImageRequest;
use App\Models\Pet;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PetService {

    public function __construct (
        private readonly PetRepository $petRepository,
        private readonly PhotoService $photoService
    ) {}

    /**
     * @param FindByStatusRequest $request
     *
     * @return array
     */
    public function findPetsByStatuses (FindByStatusRequest $request): array {

        $statuses = $request->get('statuses');
        $cursor = $request->get('cursor') ?? 0;

        return $this->petRepository->findPetsByStatusesPerCursor($statuses, $cursor);
    }

    /**
     * @param int $petId
     *
     * @return Pet
     */
    public function getPetById (int $petId): Pet {

        return $this->petRepository->getPetById($petId);
    }

    /**
     * @param PetStoreRequest $request
     *
     * @return Pet
     * @throws \Throwable
     */
    public function store (PetStoreRequest $request): Pet {

        DB::beginTransaction();
        try {

            $pet = $this->petRepository->store($request->toArray());

            $photoUrl = $this->photoService->storePhoto($request->file('image'));

            $this->petRepository->updatePhotoUrls($pet, $photoUrl);

        } catch (\Throwable $e) {

            Log::error("[PetService][store]" . "\n". $e->getMessage());

            $this->photoService->deletePhoto($photoUrl ?? '');

            DB::rollBack();

            throw $e;
        }
        DB::commit();

        return $pet;
    }

    /**
     * @param UpdateRequest $request
     * @param int $petId
     *
     * @return Pet
     */
    public function update (UpdateRequest $request, int $petId): Pet {

        return $this->petRepository->update($request->toArray(), $petId);
    }

    /**
     * @param UploadImageRequest $request
     * @param int $petId
     *
     * @return Pet
     * @throws \Throwable
     */
    public function uploadImage (UploadImageRequest $request, int $petId): Pet {

        $pet = $this->petRepository->getPetById($petId);

        try {

            $photoUrl = $this->photoService->storePhoto($request->file('image'));

            $this->petRepository->updatePhotoUrls($pet, $photoUrl);

        } catch (\Throwable $e) {

            Log::error("[PetService][uploadImage]" . "\n". $e->getMessage());

            $this->photoService->deletePhoto($photoUrl ?? '');

            throw $e;
        }

        return $pet;
    }

    /**
     * @param int $petId
     *
     * @return void
     */
    public function destroy (int $petId): void {

        $pet = $this->petRepository->getPetById($petId);

        foreach ($pet->photo_urls as $photoUrl) {

            $this->photoService->deletePhoto($photoUrl);
        }

        $this->petRepository->delete($pet);
    }
}
