<?php

    class Session{
        public static function startSession(){
            if(session_status() == PHP_SESSION_NONE){
                session_start();
            }
        }

        public static function set($key,$value){
            $_SESSION[$key] = $value;
        }

        public static function getKey($key){
            return $_SESSION[$key] ?? null;
        }

        public static function destroySession(){
            session_destroy();
        }
    }