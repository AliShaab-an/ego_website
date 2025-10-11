<?php 


    class Logger{

        public static function error($context, $message) {
            $logDir = __DIR__ . '/../../logs/';
            if (!file_exists($logDir)) mkdir($logDir, 0777, true);

            $file = $logDir . 'error.log';
            $time = date('Y-m-d H:i:s');
            $entry = "[$time] [$context] ERROR: $message\n";
            file_put_contents($file, $entry, FILE_APPEND);
        }

        public static function info($context, $message) {
            $file = __DIR__ . '/../../logs/app.log';
            $time = date('Y-m-d H:i:s');
            $entry = "[$time] [$context] INFO: $message\n";
            file_put_contents($file, $entry, FILE_APPEND);
        }
    }
    