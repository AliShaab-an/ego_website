<?php 


    class Logger{

        public static function error($context, $message) {
            $logDir = __DIR__ . '/../logs/';
            if (!file_exists($logDir)) mkdir($logDir, 0777, true);

            $file = $logDir . 'error.log';
            $time = date('Y-m-d H:i:s');
            $entry = "[$time] [$context] ERROR: $message\n";
            file_put_contents($file, $entry, FILE_APPEND);
        }

        public static function info($context, $message) {
            $logDir = __DIR__ . '/../logs/';
            if (!file_exists($logDir)) mkdir($logDir, 0777, true);
            
            $file = $logDir . 'app.log';
            $time = date('Y-m-d H:i:s');
            $entry = "[$time] [$context] INFO: $message\n";
            file_put_contents($file, $entry, FILE_APPEND);
        }

        public static function warning($context, $data = []) {
            $logDir = __DIR__ . '/../logs/';
            if (!file_exists($logDir)) mkdir($logDir, 0777, true);
            
            $file = $logDir . 'app.log';
            $time = date('Y-m-d H:i:s');
            $message = is_array($data) ? json_encode($data) : $data;
            $entry = "[$time] [$context] WARNING: $message\n";
            file_put_contents($file, $entry, FILE_APPEND);
        }
    }
    