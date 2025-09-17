<?php  



    require_once __DIR__ . '/../models/Sizes.php';


    class SizesController{

        public function addSize(){
            $name = $_POST['name'] ?? '';
            $type = $_POST['type'] ?? null;

            if($name == ''){
                return ['status' => 'error', 'message' => 'Size name required'];
            }

            $id = Sizes::create($name,$type);
            return ['status' => 'success', 'id' => $id, 'message' => 'Size added'];

        }


        public function listSizes(){
            return Sizes::getAll();
        }
    }