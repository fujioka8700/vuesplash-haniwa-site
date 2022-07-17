<?php

namespace Tests\Feature;

use App\Models\Picture;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Storage;

class PictureDetailApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function should_正しい構造のJSONを返却する()
    {
      Storage::fake('s3');

      Picture::factory()->create();
      $picture = Picture::first();

      $response = $this->getJson(route('picture.show', [
        'id' => $picture->id,
      ]));

      $response->assertStatus(200)->assertJsonFragment([
        'id' => $picture->id,
        'url' => $picture->url,
        'owner' => [
          'name' => $picture->owner->name,
        ],
      ]);
    }
}
