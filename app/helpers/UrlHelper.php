<?php 


    class UrlHelper{

        public static function redirect(string $url): void {
            if(ob_get_level()) ob_end_clean();
            header('Location: ' . $url);
            exit();
        }

        public static function asset(string $path):string {
            return BASE_URL . '/assets/' . ltrim($path, '/');
        }
    }