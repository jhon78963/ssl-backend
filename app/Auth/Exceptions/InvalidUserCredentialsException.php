<?php

namespace App\Auth\Exceptions;

use Exception;

class InvalidUserCredentialsException extends Exception
{
    protected $message = 'The provided credentials are incorrect.';

    public function render()
    {
        return response()->json([
            'message' => [$this->message],
        ], 422);
    }
}
