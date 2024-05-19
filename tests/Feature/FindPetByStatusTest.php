<?php

namespace Tests\Feature;

use App\Models\Pet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FindPetByStatusTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_find_pet_by_status(): void
    {
        // Create pets with the specified statuses
        $pets = Pet::factory()->count(2)->create(['status' => 'sold']);

        // Prepare the params
        $queryParams = [
            'cursor' => null,
            'statuses' => ['sold'],
        ];

        // Send the GET request to find pets by status
        $response = $this->get('/api/pet/find-by-status?' . http_build_query($queryParams));

        // Assert the response status is 200 OK and two elements exists
        $response->assertStatus(200)->assertJsonCount(2, 'data');
    }
}
