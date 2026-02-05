<?php

    require_once __DIR__ . '/../../app/config/path.php';
    

    class View{

        public static function render(string $view, array $data=[],string $layout= null) : void{
            extract($data, EXTR_SKIP);

            $viewFile = VIEWS . $view . '.php';
            if(!file_exists($viewFile)){
                $viewFile = VIEWS . '404.php';
            }

            if($layout){
                $layoutFile = VIEWS . $layout . '.php';
                if(!file_exists($layoutFile)){
                    throw new RuntimeException("Layout file not found: " . $layoutFile);
                }
                require $layoutFile;
                return;
            }

            require $viewFile;
        }
    }