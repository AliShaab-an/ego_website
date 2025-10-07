<?php 


    class Logger{

        private static $controllerLog = __DIR__ . '/../../logs/controller.log';
        private static $modelLog = __DIR__ . '/../../logs/model.log';
        private static $errorLog = __DIR__ . '/../../logs/debug.log';

            // --- Helper: format timestamp ---
        private static function getTimestamp() {
            return date('[Y-m-d H:i:s]');
        }

            // --- Log Controller Message ---
        public static function controller($message) {
            $line = self::getTimestamp() . " [CONTROLLER] " . $message . PHP_EOL;
            file_put_contents(self::$controllerLog, $line, FILE_APPEND);
        }

            // --- Log Model Message ---
        public static function model($message) {
            $line = self::getTimestamp() . " [MODEL] " . $message . PHP_EOL;
            file_put_contents(self::$modelLog, $line, FILE_APPEND);
        }

        // --- Log General Errors ---
        public static function error($context, $message) {
            $line = self::getTimestamp() . " [ERROR] [$context] " . $message . PHP_EOL;
            file_put_contents(self::$errorLog, $line, FILE_APPEND);
        }

        // --- Optional: clear logs (for debugging sessions) ---
        public static function clearAll() {
            file_put_contents(self::$controllerLog, "");
            file_put_contents(self::$modelLog, "");
            file_put_contents(self::$errorLog, "");
        }
    }