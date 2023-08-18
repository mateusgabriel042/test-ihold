<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthTest extends TestCase
{
  private $dataLogin = [
    'email' => 'admin123@example.com',
    'password' => 'ihold#1234',
  ];

  public function test_user_can_login()
  {
    $response = $this->postJson('/api/auth/login', $this->dataLogin);
    $response->assertStatus(200);
  }

  public function test_user_can_logout()
  {
    $response = $this->actingAs($this->user)->postJson('/api/auth/logout', []);
    $response->assertStatus(200);
  }
}
