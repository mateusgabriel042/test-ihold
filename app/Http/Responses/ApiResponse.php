<?php
namespace App\Http\Responses;

use Illuminate\Contracts\Support\Responsable;

class ApiResponse implements Responsable
{
    protected $code_response;
    protected $message;

    public function __construct($code_response, $message)
    {
        $this->code_response = $code_response;
        $this->message = $message;
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

        return response()->json([
            'code' => $this->code_response,
            'message' => $this->message,
            'data' => $response
        ], $this->code_response);
    }

    public function _transformResponseWithPagination($collection)
    {
        return [
            'page' => $collection->currentPage(),
            'size' => $collection->perPage(),
            'total' => $collection->total(),
        ];
    }
}
