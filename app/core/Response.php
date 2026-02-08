<?php

class Response
{

    public static function json(array $payload, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($payload);
        exit;
    }


    public static function ok(array $data = []): void
    {
        self::json(['status' => 'success'] + $data, 200);
    }

    public static function error(string $message, ?string $debug = null, int $status = 500): void
    {
        $payload = ['status' => 'error', 'message' => $message];
        if (defined('IS_LOCAL') && IS_LOCAL && $debug) {
            $payload['debug'] = $debug;
        }
        self::json($payload, $status);
    }
}
