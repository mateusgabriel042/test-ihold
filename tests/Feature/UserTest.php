<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class UserTest extends TestCase
{
    private $endpoint = 'users';

    public function test_user_auth_can_create_user(){
        $endpointCreate = [
            'full_name' => 'Mateus Gabriel',
            'email' => 'teste2@iholdbank.com',
            'is_admin' => false,
            'password' => '1234abcd',
            'password_confirmation' => '1234abcd',
        ];
        $response = $this->actingAs($this->user)->postJson('/api/'.$this->endpoint, $endpointCreate);
        $response->assertStatus(200);
    }

    public function test_user_auth_can_update_user(){
        $endpointFind = User::where('email', '=', 'teste2@iholdbank.com')->get()->first();
        $endpointEdit = [
            'full_name' => 'Mateus Gabriel',
            'email' => 'teste2@iholdbank.com',
            'is_admin' => false,
            'password' => '1234abcd',
            'password_confirmation' => '1234abcd',
        ];
        $response = $this->actingAs($this->user)->putJson('/api/'.$this->endpoint.'/'.$endpointFind->id, $endpointEdit);
        $response->assertStatus(200);
    }

    public function test_user_auth_can_list_users(){   
        $response = $this->actingAs($this->user)->getJson('/api/'.$this->endpoint);
        $response->assertStatus(200);
    }

    public function test_user_auth_can_show_user(){   
        $user = User::first();
        $response = $this->actingAs($this->user)->getJson('/api/'.$this->endpoint.'/'.$user->id);
        $response->assertStatus(200);
    }

    public function test_user_auth_can_search_user(){   
        $response = $this->actingAs($this->user)->getJson('/api/'.$this->endpoint.'/search/full_name/mateus');
        $response->assertStatus(200);
    }

    public function test_user_auth_can_delete_user(){   
        $endpointFind = User::where('email', '=', 'teste2@iholdbank.com')->get()->first();
        $response = $this->actingAs($this->user)->deleteJson('/api/'.$this->endpoint.'/'.$endpointFind->id);
        $endpointFind->forceDelete();
        $response->assertStatus(200);
    }
}
