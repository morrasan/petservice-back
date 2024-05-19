<?php

namespace Tests\Feature;

use App\Models\Pet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PetDestroyTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * A feature test of destroy the Pet.
     */
    public function test_destroy_pet(): void
    {
        // Create a Pet instance
        $pet = Pet::factory()->create();

        // Assert the pet is in the database
        $this->assertDatabaseHas('pets', [
            'id' => $pet->id,
        ]);

        // Send the delete request using the direct URL
        $response = $this->delete('api/pet/' . $pet->id);

        // Assert the response status is 204 No Content (successful deletion)
        $response->assertStatus(204);

        // Assert the pet is no longer in the database
        $this->assertDatabaseMissing('pets', [
            'id' => $pet->id,
        ]);
    }
}
