<?php
declare(strict_types=1);

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class ValidationException extends Exception
{
    private array $data;

    public function __construct(array $sData = [])
    {
        $this->data = $sData;

        parent::__construct('The given data was invalid.', 422);
    }

    public function render($oRequest): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $this->message,
            'data' => $this->data
        ], $this->code);
    }
}
