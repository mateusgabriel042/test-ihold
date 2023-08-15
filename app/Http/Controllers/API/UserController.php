<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserCollection;
use App\Services\UserService;
use App\Http\Responses\ApiResponse;

class UserController extends Controller
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
        $this->middleware(['permission:read-users'])->only('index');
    }
    
    public function index() {
        try{
            $users = $this->userService->getAllWithPaginate();
            $response = new ApiResponse('200', 'Listagem de usuários bem-sucedida', true);
            return $response->toResponse([
                'users' => new UserCollection($users),
                'pagination' => $response->_transformResponseWithPagination($users),
            ]);
        } catch (\Exception $e) {
            $response = new ApiResponse(500, $e->getMessage(), false);
            return $response->toResponse([]);
        }
    }

    /*public function search($option, $value) {
        try {
            $users = $this->userService->search($option, $value);

            return $this->success([
                'users' => new UserCollection($users),
                'pagination' => ['pages' => $users->lastPage()],
            ],  'Listagem de usuários realizada com sucesso!');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function store(UserRegisterRequest $request) {
        if (isset($request->validator) && $request->validator->fails())
            return $this->verifyValidation($request);

        try {
            $user = $this->userService->create($request);
        
            $role = $request->input('role_id');//id of role

            if($role != null){
                $user->roles()->attach($role);
                $user->save();
            }

            $permissions = $request->input('permissions');//id of permissions

            if($permissions != null){
                foreach ($permissions as $item) {
                    $user->permissions()->attach($item['id_permission']);
                }
            }

            return $this->success([
                'user' => new UserResource($user),
            ]);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function show($id) {
        try {
            $user = $this->userService->find($id);
            return $this->success([
                'user' => new UserResource($user),
            ]);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function update(UserUpdateRequest $request, $id) {
        if (isset($request->validator) && $request->validator->fails())
            return $this->verifyValidation($request);
        
        try {
            $user = $this->userService->update($request, $id);
        
            $role = $request->input('role_id');//id of role

            if($role != null ){
                $user->roles()->attach($role);
            }

            $permissions = $request->input('permissions');//id of permissions

            if($permissions != null){
                foreach ($permissions as $item) {
                    $user->permissions()->attach($item['id_permission']);
                }
            }

            return $this->success([
                'user' => new UserResource($user),
            ]);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    */

    public function destroy($id) {
        try{
            $this->userService->delete($id);
            $response = new ApiResponse('200', 'Registro deletado com sucesso!', false);
            return $response->toResponse([]);
        } catch (\Exception $e) {
            $response = new ApiResponse(500, $e->getMessage(), false);
            return $response->toResponse([]);
        }
    }
    
    public function multipleDeletion(Request $request) {
        try{
            $result = $this->userService->multipleDeletion($request->get('ids'));
            $response = new ApiResponse('200', 'Registros deletados com sucesso!', false);
            return $response->toResponse([]);
        } catch (\Exception $e) {
            $response = new ApiResponse(500, $e->getMessage(), false);
            return $response->toResponse([]);
        }
    }
}
