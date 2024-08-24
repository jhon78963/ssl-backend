<?php

namespace App\Auth\Exceptions;

use Exception;

class InvalidTokenException extends Exception
{
    protected $message = 'Invalid token.';

    public function render()
    {
        return response()->json([
            'message' => [$this->message],
        ], 422);
    }
}
