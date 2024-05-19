<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Pet;
use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PetUpdateTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * A feature test of update the Pet.
     */
    public function test_update_pet(): void
    {
        // Create a pet with initial data
        $pet = Pet::factory()->create([
            'name' => 'Initial Name',
            'status' => 'available',
        ]);

        // Create a category and tags
        $category = Category::factory()->create(['name' => 'Dogs']);
        $tags = Tag::factory()->count(2)->sequence(
            ['name' => 'cuteanimals'],
            ['name' => 'partyanimals']
        )->create();

        // Prepare the updated data to sent in the request
        $updatedData = [
            'name' => 'Updated Name',
            'status' => 'sold',
            'category_id' => $category->id,
            'tags' => $tags->pluck('id')->toArray(),
        ];

        // Send the POST request to update the pet
        $response = $this->postJson("/api/pet/{$pet->id}", $updatedData);

        // Assert the response status is 200 OK
        $response->assertStatus(200);

        // Assert the response contains the updated data
        $response->assertJson([
            'id' => $pet->id,
            'name' => 'Updated Name',
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

        // Assert the pet record in the database has been updated
        $this->assertDatabaseHas('pets', [
            'id' => $pet->id,
            'name' => 'Updated Name',
            'status' => 'sold',
            'category_id' => $category->id,
        ]);

        // Assert the tags associated with the pet have been updated
        $this->assertEquals($tags->pluck('id')->toArray(), $pet->fresh()->tags->pluck('id')->toArray());
    }
}
