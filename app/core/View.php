<?php
    class View{

        public static function render(string $view, array $data=[],string $layout= null) : void{
            extract($data, EXTR_SKIP);

            $viewFile = VIEWS . $view . '.php';
            if(!file_exists($viewFile)){
                $viewFile = VIEWS . 'errors/404.php';
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

        public static function partial(string $relativePath, array $data = []): void
        {
            extract($data, EXTR_SKIP);

            $file = VIEWS . trim($relativePath, '/') . '.php';
            if (!file_exists($file)) {
                throw new Exception("Partial not found: {$file}");
            }

            require $file;
        }
    }