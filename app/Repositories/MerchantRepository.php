<?php

namespace App\Repositories;

use App\Models\Merchant;
use Illuminate\Http\Request;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Requests\UserUpdateRequest;

class MerchantRepository extends BaseRepository
{

  public function __construct()
  {
    parent::__construct(new Merchant());
  }
}
