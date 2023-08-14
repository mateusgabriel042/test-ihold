<?php
namespace App\Http\Responses;

use Illuminate\Contracts\Support\Responsable;

class ApiResponse implements Responsable
{
    protected $code_response;
    protected $message;
    protected $is_pagination;


    public function __construct($code_response, $message, $is_pagination = false)
    {
        $this->code_response = $code_response;
        $this->message = $message;
        $this->is_pagination = $is_pagination;
    }

    public function toResponse($response)
    {

        if ($this->code_response != '200' && $this->code_response != '201') {
            return response()->json([
                'data' => [
                    'code' => $this->code_response,
                    'message' => $this->message
                ]
            ], $this->code_response);
        }

        if ($this->is_pagination) {
            return $this->_transformResponseWithPagination($response);
        }

        return response()->json([
            'code' => $this->code_response,
            'message' => $this->message,
            'data' => $response
        ], $this->code_response);
    }

    private function _transformResponseWithPagination($pagination)
    {
        return response()->json([
            'page' => $pagination->currentPage(),
            'size' => $pagination->perPage(),
            'total' => $pagination->total(),
            'data' => $pagination->items()
        ]);
    }
}