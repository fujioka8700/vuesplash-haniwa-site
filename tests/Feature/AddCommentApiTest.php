<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Picture;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AddCommentApiTest extends TestCase
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
  public function should_コメントを追加できる()
  {
    Storage::fake('s3');

    Picture::factory()->create();
    $picture = Picture::first();

    $content = 'sample content';

    $response = $this->actingAs($this->user)->postJson(route('picture.comment', [
      'picture' => $picture->id,
    ]), compact('content'));

    $response->assertStatus(201)->assertJsonFragment([
      // JSONフォーマットが期待通りであること
      'content' => $content,
      'author' => [
        'name' => $this->user->name,
      ],
    ]);

    $comments = $picture->comments()->get();

    // データベースにコメントが1件登録されていること
    $this->assertEquals($comments->count(), 1);

    // データベースのコメントが、APIでリクエストしたものであること
    $this->assertEquals($comments[0]->content, $content);
  }
}
