<?php

    class SettingsService
    {
        public static function all(): array
        {
            return SettingsHelper::all();
        }

        public static function get(string $key, $default = null)
        {
            return SettingsHelper::get($key, $default);
        }

        public static function updateMany(array $pairs): void
        {
            //Settings::updateMany($pairs); 
            SettingsHelper::forgetCache();
        }
    }