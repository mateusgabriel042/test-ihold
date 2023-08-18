<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\MerchantRegisterRequest;
use App\Http\Requests\MerchantUpdateRequest;
use App\Http\Resources\MerchantResource;
use App\Http\Resources\MerchantCollection;
use App\Repositories\MerchantRepository;
use App\Http\Responses\ApiResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Response;

class MerchantController extends Controller
{
    private $merchantRepository;

    public function __construct(MerchantRepository $merchantRepository)
    {
        $this->merchantRepository = $merchantRepository;
        $this->middleware(['permission:create-merchants'])->only('store');
        $this->middleware(['permission:read-merchants'])->only(['index', 'search', 'show']);
        $this->middleware(['permission:update-merchants'])->only('update');
        $this->middleware(['permission:delete-merchants'])->only(['destroy', 'multipleDeletion']);
    }
    
    public function index() {
        try{
            $merchants = $this->merchantRepository->getAllWithPaginate(['user']);
            $response = new ApiResponse(Response::HTTP_OK, 'Listagem de comerciantes bem-sucedida');
            return $response->toResponse([
                'merchants' => new MerchantCollection($merchants),
                'pagination' => $response->_transformResponseWithPagination($merchants),
            ]);
        } catch (\Exception $e) {
            $response = new ApiResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage());
            return $response->toResponse([]);
        }
    }

    public function search(String $option, $value) {
        try {
            $merchants = $this->merchantRepository->findByColumnLike($option, $value);
            $response = new ApiResponse(Response::HTTP_OK, 'Listagem de comerciantes bem-sucedida');
            return $response->toResponse([
                'merchants' => new MerchantCollection($merchants),
                'pagination' => $response->_transformResponseWithPagination($merchants),
            ]);
        } catch (\Exception $e) {
            $response = new ApiResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage());
            return $response->toResponse([]);
        }
    }

    public function store(MerchantRegisterRequest $request) {
        
        try {
            $merchant = $this->merchantRepository->save($request->all());

            $response = new ApiResponse(Response::HTTP_OK, 'Registro de comerciante bem-sucedida');
            return $response->toResponse(new MerchantResource($merchant));
        } catch (\Exception $e) {
            $response = new ApiResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage());
            return $response->toResponse([]);
        }
    }

    public function show(int $id) {
        try {
            $merchant = $this->merchantRepository->find($id);
            $response = new ApiResponse(Response::HTTP_OK, 'Comerciante encontrado');
            return $response->toResponse([
                'merchant' => new MerchantResource($merchant)
            ]);
        } catch(\Exception $e) {
            $response = new ApiResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage());
            return $response->toResponse($e);
        }
    }

    public function update(MerchantUpdateRequest $request, int $id) {
        try {
            $this->merchantRepository->update($id, $request->all());
            $response = new ApiResponse(Response::HTTP_OK, 'Comerciante atualizado com sucesso');
            $merchant = $this->merchantRepository->find($id);
            return $response->toResponse(new MerchantResource($merchant));
        } catch(\Exception $e) {
            $response = new ApiResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage());
            return $response->toResponse($e);
        }
    }

    public function destroy(int $id) {
        try{
            $this->merchantRepository->delete($id);
            $response = new ApiResponse(Response::HTTP_OK, 'Registro deletado com sucesso!');
            return $response->toResponse([]);
        } catch (\Exception $e) {
            $response = new ApiResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage());
            return $response->toResponse([]);
        }
    }
    
    public function multipleDeletion(Request $request) {
        try{
            $this->merchantRepository->multipleDeletion($request->get('ids'));
            $response = new ApiResponse(Response::HTTP_OK, 'Registros deletados com sucesso!');
            return $response->toResponse([]);
        } catch (\Exception $e) {
            $response = new ApiResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage());
            return $response->toResponse([]);
        }
    }
}
