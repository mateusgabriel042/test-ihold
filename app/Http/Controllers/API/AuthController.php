<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\UserLoginRequest;
use Illuminate\Support\Facades\Auth;
use App\Repositories\UserRepository;
use App\Http\Responses\ApiResponse;
use Illuminate\Http\Response;

class AuthController extends Controller
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function login(UserLoginRequest $request) {
        if (isset($request->validator) && $request->validator->fails())
            return $this->verifyValidation($request);

        try {
            $dataUser = $request->all();
            
            if (!Auth::attempt($dataUser)) {
                return $this->error('Credenciais não encontradas.', 401);
            }

            $user = $this->userRepository->findByColumnFirst('id', auth()->user()->id);

            $response = new ApiResponse(Response::HTTP_OK, 'Operação bem-sucedida');

            return $response->toResponse([
                'user'  => $user,
                'token' => $user->createToken('API Token')->plainTextToken,
            ]);

        } catch (\Exception $e) {
            $response = new ApiResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage());
            return $response->toResponse([]);
        }
    }

    public function logout(){
        try {
            Auth::user()->tokens->each->delete();
            // Auth::user()->currentAccessToken()->delete();
            $response = new ApiResponse(Response::HTTP_OK, 'Logout realizado com sucesso!');
            return $response->toResponse([]);
        } catch (\Exception $e) {
            $response = new ApiResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage());
            return $response->toResponse([]);
        }
    }
}
