<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Requests\UserUpdateRequest;
use Illuminate\Support\Facades\Hash;

class UserService extends AbstractService
{

  private $model;

  public function __construct($model)
  {
    parent::__construct($model);
    $this->model = $model;
  }

  public static function createUserIfNotExist($data)
  {
    $user = User::where('email', '=', $data['email'])->first();

    if ($user == null)
      $user = User::create($data);

    return $user;
  }

  public function delete($id)
  {
    $user = $this->find($id);
    $user->delete();
    return $user;
  }

  public function multipleDeletion($ids = [])
  {
    $result = User::destroy($ids);
    return $result;
  }
}
