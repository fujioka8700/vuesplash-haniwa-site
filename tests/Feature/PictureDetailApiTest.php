<?php

namespace Tests\Feature;

use App\Models\Picture;
use App\Models\Comment;
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

      Picture::factory()->create()->each(function ($picture) {
        $picture->comments()->saveMany(Comment::factory(3)->make());
      });
      $picture = Picture::first();

      $response = $this->getJson(route('picture.show', [
        'id' => $picture->id,
      ]));

      $comments = $picture->comments->sortByDesc('id')->map(function($comment) {
        return [
          'content' => $comment->content,
          'author' => [
            'name' => $comment->author->name,
          ],
        ];
      })->all();

      $response->assertStatus(200)->assertJsonFragment([
        'id' => $picture->id,
        'url' => $picture->url,
        'likes_count' => 0,
        'liked_by_user' => false,
        'owner' => [
          'name' => $picture->owner->name,
        ],
        'comments' => $comments,
      ]);
    }
}
