<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Picture;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class LikeApiTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
      parent::setUp();

      $this->user = User::factory()->create();

      Picture::factory()->create();
      $this->picture = Picture::first();
    }

    /**
     * @test
     */
    public function should_いいねを追加できる()
    {
      $response = $this->actingAs($this->user)->putJson(route('picture.like', [
        'id' => $this->picture->id,
      ]));

      $this->assertEquals($this->picture->likes()->count(), 1);
    }

    /**
     * @test
     */
    public function should_いいねを解除できる()
    {
      $a = $this->picture->likes();
      dump($a);
    }
}
