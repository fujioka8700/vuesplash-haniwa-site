<?php

namespace Tests\Feature;

use App\Models\Picture;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PicturesSubmitApiTest extends TestCase
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
  public function should_ファイルをアップロードできる()
  {
    // S3ではなくテスト用のストレージを使用する
    // storage/framework/testing/disks/s3/
    Storage::fake('s3');

    $response = $this->actingAs($this->user)->postJson(route('picture.create'), [
      // ダミーファイルを作成して送信している
      'picture' => UploadedFile::fake()->image('picture.jpg')
    ]);

    // レスポンスが201(CREATED)であること
    $response->assertStatus(201);

    $picture = Picture::first();

    // 写真のIDが12桁のランダムな文字列であること
    $this->assertMatchesRegularExpression('/^[0-9a-zA-Z-_]{12}$/', $picture->id);

    // データベースに挿入されたファイル名のファイルが、ストレージに保存されていること
    Storage::cloud()->assertExists($picture->filename);
  }

  /**
   * @test
   */
  public function should_データベースエラーの場合はファイルを保存しない()
  {
    // picturesテーブルの削除
    Schema::drop('pictures');

    Storage::fake('s3');

    $response = $this->actingAs($this->user)->postJson(route('picture.create'), [
      'picture' => UploadedFile::fake()->image('picture.jpg')
    ]);

    // レスポンスが500(INTERNAL SERVER ERROR)であること
    $response->assertStatus(500);

    // ストレージにファイルが保存されていないこと
    $this->assertEquals(0, count(Storage::cloud()->files()));
  }
}
