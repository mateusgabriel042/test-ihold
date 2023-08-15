<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserCollection;
use App\Repositories\UserRepository;
use App\Http\Responses\ApiResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Response;

class UserController extends Controller
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
        $this->middleware(['permission:read-users'])->only('index');
    }
    
    public function index() {
        try{
            $users = $this->userRepository->getAllWithPaginate();
            $response = new ApiResponse(Response::HTTP_OK, 'Listagem de usuários bem-sucedida');
            return $response->toResponse([
                'users' => new UserCollection($users),
                'pagination' => $response->_transformResponseWithPagination($users),
            ]);
        } catch (\Exception $e) {
            $response = new ApiResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage());
            return $response->toResponse([]);
        }
    }

    public function search($option, $value) {
        try {
            $users = $this->userRepository->findByColumnLike($option, $value);
            $response = new ApiResponse(Response::HTTP_OK, 'Listagem de usuários bem-sucedida');
            return $response->toResponse([
                'users' => new UserCollection($users),
                'pagination' => $response->_transformResponseWithPagination($users),
            ]);

            return $this->success([
                'users' => new UserCollection($users),
                'pagination' => $response->_transformResponseWithPagination($users),
            ],  'Listagem de usuários realizada com sucesso!');
        } catch (\Exception $e) {
            $response = new ApiResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage());
            return $response->toResponse([]);
        }
    }

    public function store(UserRegisterRequest $request) {
        if (isset($request->validator) && $request->validator->fails())
            return $this->verifyValidation($request);

        try {
            $data = $request->all();
            $data['password'] = Hash::make('ihold#1234');
            $user = $this->userRepository->save($data);

            $response = new ApiResponse(Response::HTTP_OK, 'Listagem de usuários bem-sucedida');
            return $response->toResponse([
                'user' => new UserResource($user),
            ]);
        } catch (\Exception $e) {
            $response = new ApiResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage());
            return $response->toResponse([]);
        }
    }

    public function show($id) {
        try {
            $user = $this->userRepository->find($id);
            $response = new ApiResponse(Response::HTTP_OK, 'Usuário Encontrado');
            return $response->toResponse([
                'user' => new UserResource($user)
            ]);
        } catch(\Exception $e) {
            $response = new ApiResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage());
            return $response->toResponse($e);
        }
    }

    public function update(UserUpdateRequest $request, $id) {
        if (isset($request->validator) && $request->validator->fails())
            return $this->verifyValidation($request);
        
        try {
            $data = $request->all();
            if($data['password'] != null && $data['password'] != ''){
                $data['password'] = Hash::make('ihold#1234');
            }
            $user = $this->userRepository->update($id, $data);
            $response = new ApiResponse(Response::HTTP_OK, 'Usuário atualizado com sucesso');
            return $response->toResponse([
                'user' => new UserResource($user)
            ]);
        } catch(\Exception $e) {
            $response = new ApiResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage());
            return $response->toResponse($e);
        }
    }

    public function destroy($id) {
        try{
            $this->userRepository->delete($id);
            $response = new ApiResponse(Response::HTTP_OK, 'Registro deletado com sucesso!');
            return $response->toResponse([]);
        } catch (\Exception $e) {
            $response = new ApiResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage());
            return $response->toResponse([]);
        }
    }
    
    public function multipleDeletion(Request $request) {
        try{
            $result = $this->userRepository->multipleDeletion($request->get('ids'));
            $response = new ApiResponse(Response::HTTP_OK, 'Registros deletados com sucesso!');
            return $response->toResponse([]);
        } catch (\Exception $e) {
            $response = new ApiResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage());
            return $response->toResponse([]);
        }
    }
}
