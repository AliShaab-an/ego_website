<?php 

    class ApiRunner{

        public static function run(callable $fn):void{
            try{
                $fn();
            }catch(Throwable $e){
                error_log("API Error: " . $e->getMessage() . "\n" . $e->getTraceAsString());
                Response::error($e->getMessage(), $e->getTraceAsString());
            }
        }
    }