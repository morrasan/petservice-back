<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Pet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GetPetByIdTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * A feature test for a getting the Pet by ID.
     */
    public function test_get_pet_by_id(): void
    {
        // Create a category and tags
        $category = Category::factory()->create(['name' => 'Dogs']);

        $pet = Pet::factory()->create([
            'name' => 'Jan',
            'status' => 'available',
            'category_id' => $category->id,
        ]);

        // Send the GET request to fetch the pet
        $response = $this->get("/api/pet/{$pet->id}");

        // Assert the response status is 200 OK
        $response->assertStatus(200);

        // Assert the response contains the parameters we need
        $response->assertJsonFragment([
            'id' => $pet->id,
            'name' => 'Jan',
            'status' => 'available',
            'category' => [
                'id' => $category->id,
                'name' => 'Dogs',
            ]
        ]);
    }
}
