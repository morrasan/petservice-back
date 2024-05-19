<?php

namespace Tests\Feature;

use App\Models\Pet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PetUploadImageTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * A feature test of upload image for the Pet.
     */
    public function test_upload_pet_image(): void
    {
        // Create a pet
        $pet = Pet::factory()->create();

        // Fake the storage
        Storage::fake('public');

        // Create a fake image file
        $image = UploadedFile::fake()->image('pet.jpg');

        // Send the POST request to upload the image
        $response = $this->post("/api/pet/{$pet->id}/upload-image", [
            'image' => $image,
        ]);

        // Assert the response status is 200 OK
        $response->assertStatus(200);

        // Assert the response contains the new photo_urls with the uploaded image path
        $response->assertJsonFragment(["photos/{$image->hashName()}"]);

        // Assert the image was stored in the storage
        Storage::disk('public')->assertExists("photos/{$image->hashName()}");
    }
}
