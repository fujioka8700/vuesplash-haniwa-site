<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Session;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthenticationErrorTest extends TestCase
{
  /**
   * @test
   */
  public function should_認証エラー時、トークンリフレッシュ()
  {
    $response = $this->getJson(route('reflesh-token'));
    $response->assertStatus(200);
  }
}
