<?php

namespace Tests\Feature;

use App\Models\Picture;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Storage;

class PictureListApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function should_正しい構造のJSONを返却する()
    {
      Storage::fake('s3');

      // 5枚の写真データを生成する
      Picture::factory()->count(5)->create();

      $response = $this->getJson(route('picture.index'));

      // 生成した写真データを作成日時順で取得
      $pictures = Picture::with(['owner'])->orderBy('created_at', 'desc')->get();

      // data項目の期待値
      $expected_data = $pictures->map(function($photo) {
        return [
          'id' => $photo->id,
          'url' => $photo->url,
          'likes_count' => 0,
          'owner' => [
            'name' => $photo->owner->name
          ],
        ];
      })
      ->all();

      $response->assertStatus(200)
        // レスポンスJSONのdata項目に含まれる要素が5つであること
        ->assertJsonCount(5, 'data')
        // レスポンスJSONのdata項目が期待値と合致すること
        ->assertJsonFragment([
          'data' => $expected_data
        ]);
    }
}
