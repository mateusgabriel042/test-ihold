<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Repositories\OrderRepository;
use App\Repositories\OrderItemRepository;
use App\Http\Responses\ApiResponse;

class OrderContoller extends Controller
{

    private $orderRepository;
    private $orderItemRepository;

    public function __construct(OrderRepository $orderRepository, OrderItemRepository $orderItemRepository) {
        $this->orderRepository = $orderRepository;
        $this->orderItemRepository = $orderItemRepository;

    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $orders = $this->orderRepository->all(['customer', 'orderStatus', 'orderItem']);
            $responsable = new ApiResponse(Response::HTTP_OK, 'Produto Encontrado');
            return $responsable->toResponse($orders);
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
    public function show(string $id)
    {
        try {
            $orders = $this->orderRepository->findByColumn('id', $id, ['customer', 'orderStatus', 'orderItem']);
            $responsable = new ApiResponse(Response::HTTP_OK, 'Produto Encontrado');
            return $responsable->toResponse($orders);
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
            $responsable = new ApiResponse(Response::HTTP_CREATED, 'Pedido inserido com sucesso');
            return $responsable->toResponse($order);
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
        //
    }
}
