<?php

namespace Tests\Feature;

use App\Models\User;
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

    /**
     * @test
     */
    public function should_画像5枚、各々から3個のいいねを返却する()
    {
      Storage::fake('s3');

      $this->users = User::factory(3)->create();

      Picture::factory(5)->create();
      $this->pictures = Picture::all();

      for ($j=0; $j < $this->pictures->count(); $j++) {
        for ($i=0; $i < $this->users->count(); $i++) {
          $this->actingAs($this->users[$i])->putJson(route('picture.like', [
            'id' => $this->pictures[$j]->id,
          ]));
        }
      }

      $response = $this->getJson(route('picture.index'));

      $expected_data = $this->pictures->map(function($photo) {
        return [
          'id' => $photo->id,
          'url' => $photo->url,
          'likes_count' => 3,
          'owner' => [
            'name' => $photo->owner->name
          ],
        ];
      })
      ->all();

      $response->assertStatus(200)
        ->assertJsonCount(5, 'data')
        ->assertJsonFragment([
          'data' => $expected_data
        ]);
    }
}
