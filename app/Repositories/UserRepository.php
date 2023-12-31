<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Hash;

class UserRepository extends BaseRepository
{

  public function __construct()
  {
    parent::__construct(new User());
  }

  public static function createUserIfNotExist($data)
  {
    $user = User::where('email', '=', $data['email'])->first();

    if ($user == null)
      $user = User::create($data);

    return $user;
  }

  public function multipleDeletion($ids = [])
  {
    $result = User::destroy($ids);
    return $result;
  }
}
