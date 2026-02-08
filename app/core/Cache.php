<?php

    class Cache{
        
        private static function dir(): string {
            $dir = (defined('ROOT_PATH') ? ROOT_PATH : __DIR__ . '/../../'). 'cache/';

            if(!is_dir($dir)){
                mkdir($dir, 0777, true);
            }
            return $dir;
        }

        private static function file(string $key):string{
            return self::dir() . md5($key) . 'cache';
        }



        public static function set(string $key,mixed $data,int $ttl = 3600):void{
            $payload = [
                'expire' => time() + $ttl,
                'data'   => $data,
            ];

            file_put_contents(self::file($key), serialize($payload));
        }


        public static function get(string $key): mixed{

            $filename = self::file($key);
            
            if(!file_exists($filename)) return false;

            $payload = @unserialize(file_get_contents($filename));
            if(!is_array($payload) || !isset($payload['expire'])){
                @unlink($filename);
                return false;
            }

            if ($payload['expire'] <= time()) {
                @unlink($filename);
                return false;
            }

            return $payload['data'];
        }

        public static function delete($key){
            $filename = self::file($key);
            if (file_exists($filename)) unlink($filename);
        }

        public static function remember(string $key,int $ttl,callable $callback): mixed{
            $data = self::get($key);
            if($data === false){
                $data = $callback();
                self::set($key,$data,$ttl);
            }
            return $data;
        }
    }