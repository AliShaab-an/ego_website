<?php 
    require_once __DIR__ . '/../models/Colors.php';

    class ColorsController{

        public function addColor(){
            $name = $_POST["name"] ?? '';
            $hex = $_POST['hex_code'] ?? null;
            
            if($name == ''){
                return ['status' => 'error', 'message' => 'Color name required'];
            }

            $id = Colors::createColor($name,$hex);
            return ['status' => 'success', 'id' => $id, 'message' => 'Color added'];

        }


        public function listColors(){
            return Colors::getAllColors();
        }

    }