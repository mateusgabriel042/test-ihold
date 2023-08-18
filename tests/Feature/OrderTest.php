<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Merchant;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Hash;

class OrderTest extends TestCase
{
  private $endpoint = 'orders';

  public function test_user_auth_can_create_product()
  {
    $userTest = [
      'full_name' => 'Jhon Doe',
      'email' => 'teste_order@iholdbank.com',
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

    $productTest = [
      'product_status_id' => 1,
      'merchant_id' => $merchant->id,
      'name' => 'Tesoura',
      'price' => 17.8,
    ];

    $product = Product::create($productTest);

    $dataOrder = [
      'order' => [
        "order_status_id" => 1,
        "customer_id" => 1,
      ],
      'products' => [
        [
          "product_id" => $product->id,
          "quantity" => 2
        ]
      ]
    ];


    $response = $this->actingAs($this->user)->postJson('/api/' . $this->endpoint, $dataOrder);
    $response->assertStatus(201);
  }

  public function test_user_auth_can_show_order()
  {
    $order = Order::first();
    $response = $this->actingAs($this->user)->getJson('/api/' . $this->endpoint . '/' . $order->id);
    $response->assertStatus(200);
  }

  public function test_user_auth_can_list_orders()
  {
    $response = $this->actingAs($this->user)->getJson('/api/' . $this->endpoint);
    $response->assertStatus(200);
  }

  public function test_delete_order()
  {
    $user = User::where('email', '=', 'teste_order@iholdbank.com')->get()->first();
    $merchant = Merchant::where('user_id', '=', $user->id)->get()->first();
    $product = Product::where('merchant_id', '=', $merchant->id)->get()->first();
    $order = Order::where('customer_id', '=', 1)->get()->first();
    OrderItem::where('order_id', '=', $order->id)->forceDelete();
    $order->forceDelete();
    $product->forceDelete();
    $merchant->forceDelete();
    $user->forceDelete();
  }
}
