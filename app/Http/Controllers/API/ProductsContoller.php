<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Repositories\ProductRepository;
use App\Http\Responses\ApiResponse;
use App\Http\Resources\ProductCollection;
use App\Http\Requests\ProductRequest;

class ProductsContoller extends Controller
{

    private $productRepository;

    public function __construct(ProductRepository $productRepository) {
        $this->productRepository = $productRepository;
        $this->middleware(['permission:create-products'])->only('store');
        $this->middleware(['permission:read-products'])->only(['index', 'search', 'show']);
        $this->middleware(['permission:update-products'])->only('update');
        $this->middleware(['permission:delete-products'])->only(['destroy', 'multipleDeletion']);
    }
    public function index() {
        try{
            $products = $this->productRepository->getAllWithPaginate(['productStatus', 'merchant']);
            $response = new ApiResponse(Response::HTTP_OK, 'Listagem de produtos bem-sucedida', true);
            return $response->toResponse([
                'products' => new ProductCollection($products),
                'pagination' => $response->_transformResponseWithPagination($products),
            ]);
        } catch (\Exception $e) {
            $response = new ApiResponse(RESPONSE::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage(), false);
            return $response->toResponse([]);
        }
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request)
    {
        try {
            $product = $this->productRepository->save($request->all());
            $responsable = new ApiResponse(Response::HTTP_CREATED, 'Produto adicionado com sucesso');
            return $responsable->toResponse($product);
        } catch(\Exception $e) {
            $responsable = new ApiResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage());
            return $responsable->toResponse($e);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        try {
            $product = $this->productRepository->find($id);

            $response = new ApiResponse(Response::HTTP_OK, 'Produto encontrado');
            return $response->toResponse(
                new ProductCollection($product)
            );
        } catch(\Exception $e) {
            $response = new ApiResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage());
            return $response->toResponse($e);
        }
    }

    public function search(String $option, $value) {
        try {
            $products = $this->productRepository->findByColumnLike($option, $value, ['productStatus', 'merchant']);
            $response = new ApiResponse(Response::HTTP_OK, 'Busca de produtos bem-sucedida');
            return $response->toResponse([
                'merchants' => new ProductCollection($products),
                'pagination' => $response->_transformResponseWithPagination($products),
            ]);
        } catch (\Exception $e) {
            $response = new ApiResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage());
            return $response->toResponse([]);
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(ProductRequest $request, int $id)
    {
        try {
            $this->productRepository->update($id, $request->all());
            $product = $this->productRepository->find($id);
            $response = new ApiResponse(Response::HTTP_OK, 'Produto atualizado');
            return $response->toResponse(
                new ProductCollection($product)
            );
        } catch(\Exception $e) {
            $responsable = new ApiResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage());
            return $responsable->toResponse($e);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        try {
            $this->productRepository->delete($id);
            $responsable = new ApiResponse(Response::HTTP_OK, 'Produto excluido com sucesso');
            return $responsable->toResponse([]);
        } catch(\Exception $e) {
            $responsable = new ApiResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage());
            return $responsable->toResponse($e);
        }
    }

    public function multipleDeletion(Request $request) {
        try{
            $this->productRepository->multipleDeletion($request->get('ids'));
            $responsable = new ApiResponse(Response::HTTP_OK, 'Produtos excluidos com sucesso');
            return $responsable->toResponse([]);
        } catch (\Exception $e) {
            $responsable = new ApiResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage());
            return $responsable->toResponse([]);
        }
    }
}
