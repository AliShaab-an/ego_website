<?php 
    require_once __DIR__ . '/../models/Colors.php';

    class ColorsController{

        public function addColor(){

            $name = isset($_POST['name']) ? ucfirst(strtolower(trim($_POST['name']))) : '';
            $hex = isset($_POST['hex_code']) ? strtoupper(trim($_POST['hex_code'])) : '';
            
            if($name == ''){
                return ['status' => 'error', 'message' => 'Color name required'];
            }
            if ($hex === '') {
                return ['status' => 'error', 'message' => 'Hex code is required.'];
            }
            if (!preg_match('/^#([A-Fa-f0-9]{6})$/', $hex)) {
                return ['status' => 'error', 'message' => 'Invalid hex color format.'];
            }

            try{
                $existing = Colors::findByName($name);

                if ($existing) {
                    return ['status' => 'error', 'message' => 'Color already exists.'];
                }

                $id = Colors::createColor($name,$hex);

                return [
                    'status'  => 'success',
                    'id'      => $id,
                    'message' => 'Color added successfully.'
                ];
            }catch(PDOException $e){
                return ['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()];
            }catch (Exception $e) {
                return ['status' => 'error', 'message' => $e->getMessage()];
            }
        }


        public function listColors(){
            try{
                return Colors::getAllColors();
            }catch(Exception $e){
                return ['status' => 'error', 'message' => $e->getMessage()];
            }
            
        }

        public function deleteColor(){
            $id = $_POST['id'] ?? null;

            if (!$id) {
                return ['status' => 'error', 'message' => 'Color ID is required.'];
            }

            try {
                Colors::deleteColor($id);
                return ['status' => 'success', 'message' => 'Color deleted successfully.'];
            } catch (Exception $e) {
                return ['status' => 'error', 'message' => $e->getMessage()];
            }
        }

        public function updateColor(){
            $id   = $_POST['id'] ?? null;
            $name = ucfirst(strtolower(trim($_POST['name'])));
            $hex  = strtoupper(trim($_POST['hex_code']));

            if (empty($id) || empty($name) || empty($hex)) {
                return ['status' => 'error', 'message' => 'All fields are required.'];
            }

            try {
                Colors::updateColor($id, $name, $hex);
                return ['status' => 'success', 'message' => 'Color updated successfully.'];
            } catch (Exception $e) {
                return ['status' => 'error', 'message' => $e->getMessage()];
            }
        }

    }