<?php

namespace App\Http\Repositories;

use App\Models\Pet;
use Illuminate\Database\Eloquent\Collection;

class PetRepository {

    private $petLimit = 10;

    /**
     * @param int $id
     *
     * @return Pet|null
     */
    public function getPetById (int $id): Pet|null {

        return Pet::find($id);
    }

    /**
     * @param array $statuses
     * @param int $cursor
     *
     * @return array
     */
    public function findPetsByStatusesPerCursor (array $statuses, int $cursor): array {

        // get pets starting from the current {cursor}, with {petLimit} elements each
        $pets = Pet::whereIn('status', $statuses)->with('category', 'tags')->where('id', '>', $cursor)->take($this->petLimit)->get();

        // get the next cursor for use in subsequent requests
        $nextCursor = $pets->isNotEmpty() ? $pets->last()->id : null;

        return [
            $pets,
            $nextCursor
        ];
    }

    /**
     * @param array $data
     *
     * @return Pet
     */
    public function store (array $data): Pet {

        $pet = new Pet();

        $pet->name = $data['name'];
        $pet->category_id = $data['category_id'];
        $pet->status = $data['status'];

        $pet->save();

        $this->setTags($pet, $data);

        return $pet;
    }

    /**
     * @param array $data
     * @param int $id
     *
     * @return Pet
     */
    public function update (array $data, int $id): Pet {

        $pet = $this->getPetById($id);

        $pet->name = $data['name'];
        $pet->category_id = $data['category_id'];
        $pet->status = $data['status'];

        $pet->save();

        $this->setTags($pet, $data);

        return $pet;
    }

    /**
     * @param Pet $pet
     * @param string $photoUrl
     *
     * @return void
     */
    public function updatePhotoUrls (Pet $pet, string $photoUrl): void {

        $photoUrls = $pet->photo_urls;

        if (is_array($photoUrls)) {

            $photoUrls[] = $photoUrl;

            $pet->photo_urls = $photoUrls;

        } else {

            $pet->photo_urls = [$photoUrl];
        }

        $pet->save();
    }

    /**
     * @param Pet $pet
     *
     * @return void
     */
    public function delete (Pet $pet): void {

        $pet->delete();
    }

    /**
     * @param Pet $pet
     * @param array $data
     *
     * @return void
     */
    private function setTags (Pet $pet, array $data): void {

        if (isset($data['tags'])) {

            $pet->tags()->sync(array_filter($data['tags']));
        }
    }
}
