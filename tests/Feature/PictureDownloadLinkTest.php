<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Picture;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Storage;

class PictureDownloadLinkTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
      parent::setUp();

      $this->user = User::factory()->create();
    }

    /**
     * @test
     */
    public function should_アップロードした写真をダウンロードする()
    {
      Storage::fake('s3');

      $fakeImage = UploadedFile::fake()->image('picture.jpg');

      $response = $this->actingAs($this->user)->postJson(route('picture.create'), [
        'picture' => $fakeImage,
      ]);

      $response->assertStatus(201);

      $picture = Picture::first();

      $response = $this->get("/pictures/$picture->id/download");

      $this->assertEquals($fakeImage->get(), $response->content());
    }
}
