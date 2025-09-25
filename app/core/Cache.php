<?php


    class Cache{
        private static $cacheDir = __DIR__ . '/../../cache/';

        public static function set($key, $data, $ttl = 3600){
            $filename = self::$cacheDir . md5($key) . '.cache';
            $content = [
                'expire' => time() + $ttl,
                'data' => $data
            ];
            file_put_contents($filename, serialize($content));
        }


        public static function get($key){
            $filename = self::$cacheDir . md5($key) . '.cache';
            if (file_exists($filename)) {
                $content = unserialize(file_get_contents($filename));
                if ($content['expire'] > time()) {
                    return $content['data'];
                }else{
                    unlink($filename);
                }
            }
            return false;
        }

        public static function delete($key){
            $filename = self::$cacheDir . md5($key) . '.cache';
            if (file_exists($filename)) {
                unlink($filename);
            }
        }

        public static function remember($key,$ttl,$callback){
            $data = self::get($key);
            if($data === false){
                $data = $callback();
                self::set($key,$data,$ttl);
            }
            return $data;
        }
    }