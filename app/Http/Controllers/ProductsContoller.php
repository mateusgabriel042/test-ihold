<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Repositories\ProductRepository;
use App\Http\Responses\ApiResponse;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ProductCollection;
use App\Models\Product;

class ProductsContoller extends Controller
{

    private $productRepository;
    private $productCollection;


    public function __construct(ProductRepository $productRepository) {
        $this->productRepository = $productRepository;

    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $product = $this->productRepository->getProducts();
            $responsable = new ApiResponse(Response::HTTP_OK, 'Produtos encontrados', true);
            //return new ProductResource(Product::paginate(10));
            //return new ProductCollection(Product::all());

            $products = Product::all();

            foreach($products as $product) {
                dd($product->id, $product->name);
            }
            //return new ProductCollection(Product::all());


            //return $responsable->toResponse($product);
        } catch(\Exception $e) {
            $responsable = new ApiResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage());
            return $responsable->toResponse($e);
        }
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
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
    public function show(string $id)
    {
        try {
            $product = $this->productRepository->find($id);
            $responsable = new ApiResponse(Response::HTTP_OK, 'Produto Encontrado');
            return $responsable->toResponse($product);
        } catch(\Exception $e) {
            $responsable = new ApiResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage());
            return $responsable->toResponse($e);
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $this->productRepository->update($id, $request->all());
            $product = $this->productRepository->find($id);
            $responsable = new ApiResponse(Response::HTTP_OK, 'Produto atualizado com sucesso');
            return $responsable->toResponse($product);
        } catch(\Exception $e) {
            $responsable = new ApiResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage());
            return $responsable->toResponse($e);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
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
}
