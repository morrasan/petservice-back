<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PetStoreTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        // Reset auto-increment for pets table
        DB::statement('ALTER TABLE pets AUTO_INCREMENT = 1');
    }

    /**
     * A feature test of store the Pet.
     */
    public function test_store_pet(): void
    {
        // Fake the storage
        Storage::fake('public');

        // Create a category and tags
        $category = Category::factory()->create(['name' => 'Dogs']);

        $tags = Tag::factory()->count(2)->sequence(
            ['name' => 'cuteanimals'],
            ['name' => 'partyanimals']
        )->create();

        // Create a fake image file
        $image = UploadedFile::fake()->image('pet.jpg');

        // Prepare the data to be sent in the request
        $petData = [
            'name' => 'Jan',
            'image' => $image,
            'status' => 'sold',
            'category_id' => $category->id,
            'tags' => $tags->pluck('id')->toArray(),
        ];

        // Send the POST request to create a pet
        $response = $this->postJson('/api/pet', $petData);

        // Assert the response status is 201 Created
        $response->assertStatus(201);

        // Retrieve the saved photo path
        $photoPath = 'photos/' . $image->hashName();

        // Assert the response structure and values
        $response->assertJson([
            'id' => 1,
            'name' => 'Jan',
            'photo_urls' => [$photoPath],
            'status' => 'sold',
            'category' => [
                'id' => $category->id,
                'name' => 'Dogs',
            ],
            'tags' => [
                ['id' => $tags[0]->id, 'name' => 'cuteanimals'],
                ['id' => $tags[1]->id, 'name' => 'partyanimals'],
            ],
        ]);

        // Assert the pet was created in the database
        $this->assertDatabaseHas('pets', [
            'id' => 1,
            'name' => 'Jan',
            'status' => 'sold',
            'category_id' => $category->id,
            'photo_urls' => json_encode([$photoPath]),
        ]);

        // Assert the tags were associated with the pet in the pivot table
        foreach ($tags as $tag) {
            $this->assertDatabaseHas('pet_tag', [
                'pet_id' => 1,
                'tag_id' => $tag->id,
            ]);
        }

        // Assert the file was stored
        Storage::disk('public')->assertExists($photoPath);
    }
}
