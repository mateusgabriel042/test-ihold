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
        $this->middleware(['permission:create-users'])->only('store');
        $this->middleware(['permission:read-users'])->only(['index', 'search', 'show']);
        $this->middleware(['permission:update-users'])->only('update');
        $this->middleware(['permission:delete-users'])->only(['destroy', 'multipleDeletion']);
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
        } catch (\Exception $e) {
            $response = new ApiResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage());
            return $response->toResponse([]);
        }
    }

    public function store(UserRegisterRequest $request) {
        
        try {
            $data = $request->all();
            $data['password'] = Hash::make($data['password']);
            $user = $this->userRepository->save($data);

            $response = new ApiResponse(Response::HTTP_OK, 'Registro de usuário bem-sucedida');
            return $response->toResponse(new UserResource($user));
        } catch (\Exception $e) {
            $response = new ApiResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage());
            return $response->toResponse([]);
        }
    }

    public function show(int $id) {
        try {
            $user = $this->userRepository->find($id);
            $response = new ApiResponse(Response::HTTP_OK, 'Usuário encontrado');
            return $response->toResponse([
                'user' => new UserResource($user)
            ]);
        } catch(\Exception $e) {
            $response = new ApiResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage());
            return $response->toResponse($e);
        }
    }

    public function update(UserUpdateRequest $request, int $id) {
        
        try {
            $data = $request->all();
            if($data['password'] != null && $data['password'] != '')
                $data['password'] = Hash::make($data['password']);
            
            $this->userRepository->update($id, $data);
            $response = new ApiResponse(Response::HTTP_OK, 'Usuário atualizado com sucesso');
            $user = $this->userRepository->find($id);
            return $response->toResponse(new UserResource($user));
        } catch(\Exception $e) {
            $response = new ApiResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage());
            return $response->toResponse($e);
        }
    }

    public function destroy(int $id) {
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
            $this->userRepository->multipleDeletion($request->get('ids'));
            $response = new ApiResponse(Response::HTTP_OK, 'Registros deletados com sucesso!');
            return $response->toResponse([]);
        } catch (\Exception $e) {
            $response = new ApiResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage());
            return $response->toResponse([]);
        }
    }
}
