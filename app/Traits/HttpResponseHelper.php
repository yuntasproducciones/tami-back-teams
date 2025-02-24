<?php

namespace App\Traits;

trait HttpResponseHelper
{
    protected $data = null;
    protected $message = '';
    protected $success = true;
    protected $statusCode = 200;

    public static function make()
    {
        return new static();
    }

    public function successfulResponse($message = '', $data = null)
    {
        $this->success = true;
        $this->statusCode = 200;
        $this->message = $message;
        $this->data = $data;
        return $this;
    }

    public function internalErrorResponse($message = '')
    {
        $this->success = false;
        $this->statusCode = 500;
        $this->message = $message;
        return $this;
    }

    public function send()
    {
        return response()->json([
            'success' => $this->success,
            'message' => $this->message,
            'data' => $this->data
        ], $this->statusCode);
    }
}
