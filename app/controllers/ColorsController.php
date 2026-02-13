<?php 

    class ColorsController{

        public function listColors(){
            // If 'all' parameter is set, return all colors without pagination (for dropdowns)
            if (isset($_GET['all']) && $_GET['all'] === 'true') {
                // Cache all colors for 1 hour (used in shop filters)
                $data = Cache::remember('shop:colors:all', 3600, fn() => Colors::getAllColors());
                return [
                    'status' => 'success',
                    'data' => $data,
                    'total' => count($data)
                ];
            }

            // Otherwise, use pagination (for table views) - NOT cached as it's admin only
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 5;
            $offset = ($page - 1) * $limit;

            $data = Colors::getPaginated($limit, $offset);
            $total = Colors::countAll();
            $hasMore = ($offset + $limit) < $total;

            return [
                'status' => 'success',
                'data' => $data,
                'total' => $total,
                'has_more' => $hasMore
            ];
        }

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

            $existing = Colors::findByName($name);

            if ($existing) {
                return ['status' => 'error', 'message' => 'Color already exists.'];
            }

            $id = Colors::createColor($name,$hex);

            // Invalidate colors cache
            Cache::delete('shop:colors:all');

            return [
                'status'  => 'success',
                'id'      => $id,
                'message' => 'Color added successfully.'
            ];
        }

        public function deleteColor(){
            $id = $_POST['id'] ?? null;

            if (!$id) {
                return ['status' => 'error', 'message' => 'Color ID is required.'];
            }

            Colors::deleteColor($id);
            
            // Invalidate colors cache
            Cache::delete('shop:colors:all');
            
            return ['status' => 'success', 'message' => 'Color deleted successfully.'];
        }

        public function updateColor(){
            $id   = $_POST['id'] ?? null;
            $name = ucfirst(strtolower(trim($_POST['name'])));
            $hex  = strtoupper(trim($_POST['hex_code']));

            if (empty($id) || empty($name) || empty($hex)) {
                return ['status' => 'error', 'message' => 'All fields are required.'];
            }

            Colors::updateColor($id, $name, $hex);
            
            // Invalidate colors cache
            Cache::delete('shop:colors:all');
            
            return ['status' => 'success', 'message' => 'Color updated successfully.'];
        }

    }