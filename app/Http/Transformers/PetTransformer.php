<?php

namespace App\Http\Transformers;

use App\Models\Pet;

class PetTransformer {

    /**
     * @param Pet $pet
     *
     * @return array
     */
    public static function transform(Pet $pet): array {

        $transformData = [
            'id'            => $pet->id,
            'name'          => $pet->name,
            'photo_urls'    => $pet->photo_urls,
            'status'        => $pet->status,
            'category'      => [
                'id'    => $pet->category->id,
                'name'  => $pet->category->name
            ],
            'tags'          => []
        ];

        $tags = $pet->tags;

        foreach ($tags as $tag) {

            $transformData['tags'][] = [
                'id'    => $tag->id,
                'name'  => $tag->name
            ];
        }

        return $transformData;
    }

}
