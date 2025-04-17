<?php

namespace PromptBuilder\core;

class Response {
    public function json($data, $status = 200)
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    public function send($message, $status = 200)
    {
        http_response_code($status);
        echo $message;
        exit;
    }
}