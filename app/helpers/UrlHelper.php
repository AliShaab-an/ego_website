<?php 


    class UrlHelper{

        public static function redirect(string $url): void {
            if(ob_get_level()) ob_end_clean();
            header('Location: ' . $url);
            exit();
        }

        public static function asset(string $path):string {
            // Remove leading slash if present
            $path = ltrim($path, '/');
            
            // If path doesn't start with 'assets/', prepend it
            if (strpos($path, 'assets/') !== 0) {
                $path = 'assets/' . $path;
            }
            
            return PUBLIC_URL . $path;
        }
    }