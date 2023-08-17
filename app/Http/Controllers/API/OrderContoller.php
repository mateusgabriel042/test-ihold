<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Repositories\OrderRepository;
use App\Repositories\OrderItemRepository;
use App\Http\Resources\OrderResource;
use App\Http\Resources\OrderCollection;
use App\Http\Responses\ApiResponse;

class OrderContoller extends Controller
{

    private $orderRepository;
    private $orderItemRepository;

    public function __construct(OrderRepository $orderRepository, OrderItemRepository $orderItemRepository) {
        $this->orderRepository = $orderRepository;
        $this->orderItemRepository = $orderItemRepository;
        $this->middleware(['permission:create-orders'])->only('store');
        $this->middleware(['permission:read-orders'])->only(['index', 'search', 'show']);
        $this->middleware(['permission:update-orders'])->only('update');
        $this->middleware(['permission:delete-orders'])->only(['destroy', 'multipleDeletion']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $orders = $this->orderRepository->getAllWithPaginate(['orderStatus', 'customer', 'orderItem']);
            $response = new ApiResponse(Response::HTTP_OK, 'Produto Encontrado');
            return $response->toResponse([
                'orders' => new OrderCollection($orders),
                'pagination' => $response->_transformResponseWithPagination($orders),
            ]);
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
            if(!$request->order) {
                $responsable = new ApiResponse(Response::HTTP_UNPROCESSABLE_ENTITY, 'Favor enviar dados do pedido');
                return $responsable->toResponse([]);
            }

            if(!$request->products) {
                $responsable = new ApiResponse(Response::HTTP_UNPROCESSABLE_ENTITY, 'Favor selecionar pelo menos um produto');
                return $responsable->toResponse([]);
            }

            $order = $this->orderRepository->save($request->order);

            $this->orderItemRepository->saveMultipleItems($order, $request->products);
            $responsable = new ApiResponse(Response::HTTP_CREATED, 'Pedido inserido com sucesso');
            return $responsable->toResponse($order);
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
            $order = $this->orderRepository->findByColumn('id', $id, ['customer', 'orderStatus', 'orderItem']);
            $response = new ApiResponse(Response::HTTP_OK, 'Produto Encontrado');
            return $response->toResponse(
                new OrderCollection($order)
            );
        } catch(\Exception $e) {
            $responsable = new ApiResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage());
            return $responsable->toResponse($e);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        try {
            if(!$request->order) {
                $responsable = new ApiResponse(Response::HTTP_UNPROCESSABLE_ENTITY, 'Favor enviar dados do pedido');
                return $responsable->toResponse([]);
            }

            if(!$request->products) {
                $responsable = new ApiResponse(Response::HTTP_UNPROCESSABLE_ENTITY, 'Favor selecionar pelo menos um produto');
                return $responsable->toResponse([]);
            }

            $order = $this->orderRepository->find($id);
            $this->orderRepository->update($id, $request->order);
            $this->orderItemRepository->deleteByColumn('order_id', $id);

            $this->orderItemRepository->saveMultipleItems($order, $request->products);
            $response = new ApiResponse(Response::HTTP_CREATED, 'Pedido inserido com sucesso');
            return $response->toResponse(
                new OrderCollection($order)
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
        //
    }
}
