<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\UserLoginRequest;
use Illuminate\Support\Facades\Auth;
use App\Repositories\UserRepository;
use App\Http\Responses\ApiResponse;

class AuthController extends Controller
{
    private $userService;

    public function __construct(UserRepository $userService)
    {
        $this->userService = $userService;
    }

    public function login(UserLoginRequest $request) {
        if (isset($request->validator) && $request->validator->fails())
            return $this->verifyValidation($request);

    	$dataUser = $request->all();

        if (!Auth::attempt($dataUser)) {
            return $this->error('Credenciais não encontradas.', 401);
        }

        $user = $this->userService->findByColumnFirst('id', auth()->user()->id);

        $response = new ApiResponse('200', 'Operação bem-sucedida', false);

        return $response->toResponse([
            'user'  => $user,
            'token' => $user->createToken('API Token')->plainTextToken,
        ]);
    }

    public function logout(){
        Auth::user()->tokens->each->delete();
        // Auth::user()->currentAccessToken()->delete();
        $response = new ApiResponse('200', 'Operação bem-sucedida', false);
        return $response->toResponse([]);
    }
}
