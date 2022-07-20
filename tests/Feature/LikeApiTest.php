<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Picture;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
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

      $response->assertStatus(200)->assertJsonFragment([
        'picture_id' => $this->picture->id
      ]);

      $this->assertEquals($this->picture->likes()->count(), 1);
    }

    /**
     * @test
     */
    public function should_いいねを解除できる()
    {
      $this->picture->likes()->attach($this->user);

      $response = $this->actingAs($this->user)->deleteJson(route('picture.like', [
        'id' => $this->picture->id,
      ]));

      $response->assertStatus(200)->assertJsonFragment([
        'picture_id' => $this->picture->id
      ]);

      $this->assertEquals($this->picture->likes()->count(), 0);
    }

    /**
     * @test
     */
    public function should_2回同じ写真にいいねしても1個しかいいねがつかない()
    {
      $param = ['id' => $this->picture->id];
      $response = $this->actingAs($this->user)->putJson(route('picture.like', $param));
      $response = $this->actingAs($this->user)->putJson(route('picture.like', $param));

      $this->assertEquals($this->picture->likes()->count(), 1);
    }
}
