<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Merchant;
use App\Models\Product;
use Illuminate\Support\Facades\Hash;

class ProductTest extends TestCase
{
  private $endpoint = 'products';

  public function test_user_auth_can_create_product()
  {
    $userTest = [
      'full_name' => 'Jhon Doe',
      'email' => 'teste_product@iholdbank.com',
      'is_admin' => false,
      'password' => Hash::make('1234abcd'),
      'password_confirmation' => Hash::make('1234abcd'),
    ];

    $user = User::create($userTest);

    $merchantTest = [
      'merchant_name' => 'Jhon Doe',
      'user_id' => $user->id,
    ];

    $merchant = Merchant::create($merchantTest);

    $dataProduct = [
      'product_status_id' => 1,
      'merchant_id' => $merchant->id,
      'name' => 'Tesoura',
      'price' => 17.8,
    ];

    $response = $this->actingAs($this->user)->postJson('/api/' . $this->endpoint, $dataProduct);
    $response->assertStatus(201);
  }

  public function test_user_auth_can_update_product()
  {
    $user = User::where('email', '=', 'teste_product@iholdbank.com')->get()->first();
    $merchant = Merchant::where('user_id', '=', $user->id)->get()->first();
    $endpointFind = Product::where('merchant_id', '=', $merchant->id)->get()->first();
    $endpointEdit = [
      'product_status_id' => 1,
      'merchant_id' => $merchant->id,
      'name' => 'Tesoura',
      'price' => 17.8,
    ];
    $response = $this->actingAs($this->user)->putJson('/api/' . $this->endpoint . '/' . $endpointFind->id, $endpointEdit);
    $response->assertStatus(200);
  }

  public function test_user_auth_can_list_products()
  {
    $response = $this->actingAs($this->user)->getJson('/api/' . $this->endpoint);
    $response->assertStatus(200);
  }

  public function test_user_auth_can_show_product()
  {
    $product = Product::first();
    $response = $this->actingAs($this->user)->getJson('/api/' . $this->endpoint . '/' . $product->id);
    $response->assertStatus(200);
  }

  public function test_user_auth_can_search_product()
  {
    $response = $this->actingAs($this->user)->getJson('/api/' . $this->endpoint . '/search/name/tesoura');
    $response->assertStatus(200);
  }

  public function test_user_auth_can_delete_product()
  {
    $user = User::where('email', '=', 'teste_product@iholdbank.com')->get()->first();
    $merchant = Merchant::where('user_id', '=', $user->id)->get()->first();
    $endpointFind = Product::where('merchant_id', '=', $merchant->id)->get()->first();
    $response = $this->actingAs($this->user)->deleteJson('/api/' . $this->endpoint . '/' . $endpointFind->id);
    $endpointFind->forceDelete();
    $merchant->forceDelete();
    $user->forceDelete();
    $response->assertStatus(200);
  }
}
