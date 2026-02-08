<?php 

    class SettingsHelper{

        public static function get(string $key, $default = null){
            $settings = Cache::remember('settings',300,fn() => Settings::getAll());
            return $settings[$key] ?? $default;
        }

        public static function all(): array{
            return Cache::remember('settings', 300, fn() => Settings::getAll());
        }


        public static function forgetCache():void{
            Cache::delete('settings');
        }
    }