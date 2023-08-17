<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Merchant;
use Illuminate\Support\Facades\Hash;

class MerchantTest extends TestCase
{
    private $endpoint = 'merchants';

    public function test_user_auth_can_create_merchant(){
        $userTest = [
          'full_name' => 'Jhon Doe',
          'email' => 'teste@iholdbank.com',
          'is_admin' => false,
          'password' => Hash::make('1234abcd'),
          'password_confirmation' => Hash::make('1234abcd'),
        ];
        
        $user = User::create($userTest);

        $endpointCreate = [
          'merchant_name' => 'Jhon Doe',
          'user_id' => $user->id,
        ];
        $response = $this->actingAs($this->user)->postJson('/api/'.$this->endpoint, $endpointCreate);
        $response->assertStatus(200);
    }

    public function test_user_auth_can_update_merchant(){
      $user = User::where('email', '=', 'teste@iholdbank.com')->get()->first();
      $endpointFind = Merchant::where('user_id', '=', $user->id)->get()->first();
      $endpointEdit = [
        'merchant_name' => 'Jhon Doe',
        'user_id' => $user->id,
      ];
      $response = $this->actingAs($this->user)->putJson('/api/'.$this->endpoint.'/'.$endpointFind->id, $endpointEdit);
      $response->assertStatus(200);
    }

    public function test_user_auth_can_list_merchants(){   
        $response = $this->actingAs($this->user)->getJson('/api/'.$this->endpoint);
        $response->assertStatus(200);
    }

    public function test_user_auth_can_show_merchant(){   
        $user = Merchant::first();
        $response = $this->actingAs($this->user)->getJson('/api/'.$this->endpoint.'/'.$user->id);
        $response->assertStatus(200);
    }

    public function test_user_auth_can_search_merchant(){   
        $response = $this->actingAs($this->user)->getJson('/api/'.$this->endpoint.'/search/merchant_name/mateus');
        $response->assertStatus(200);
    }

    public function test_user_auth_can_delete_merchant(){   
        $user = User::where('email', '=', 'teste@iholdbank.com')->get()->first();
        $endpointFind = Merchant::where('user_id', '=', $user->id)->get()->first();
        $response = $this->actingAs($this->user)->deleteJson('/api/'.$this->endpoint.'/'.$endpointFind->id);
        $endpointFind->forceDelete();
        $user->forceDelete();
        $response->assertStatus(200);
    }
}
