<?php  
    class SizesController{

        public function listSizes(){
            // If 'all' parameter is set, return all sizes without pagination (for dropdowns)
            if (isset($_GET['all']) && $_GET['all'] === 'true') {
                // Cache all sizes for 1 hour (used in shop filters)
                $data = Cache::remember('shop:sizes:all', 3600, fn() => Sizes::getAll());
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

            $data = Sizes::getPaginated($limit, $offset);
            $total = Sizes::countAll();
            $hasMore = ($offset + $limit) < $total;

            return [
                'status' => 'success',
                'data' => $data,
                'total' => $total,
                'has_more' => $hasMore
            ];
        }

        public function addSize(){
            $name =  isset($_POST['name']) ? ucfirst(strtolower(trim($_POST['name']))) : '';
            $type = isset($_POST['type']) ? ucfirst(strtolower(trim($_POST['type']))) : '';

            if($name == ''){
                return ['status' => 'error', 'message' => 'Size name required'];
            }

            if($type == ''){
                return ['status' => 'error', 'message' => 'Size type required'];
            }

            $existing = Sizes::findByNameAndType($name, $type);
            if ($existing) {
                return [
                    'status'  => 'error',
                    'message' => "Size '{$name}' for type '{$type}' already exists."
                ];
            }

            $id = Sizes::create($name, $type);

            // Invalidate sizes cache
            Cache::delete('shop:sizes:all');

            return [
                'status'  => 'success',
                'id'      => $id,
                'message' => 'Size added successfully.'
            ];
        }


        public function updateSize(){
            $id   = $_POST['id'] ?? null;
            $name = ucfirst(strtolower(trim($_POST['name']))) ?? '';
            $type = ucfirst(strtolower(trim($_POST['type']))) ?? '';

            if (empty($id) || empty($name) || empty($type)) {
                return ['status' => 'error', 'message' => 'All fields are required.'];
            }

            Sizes::updateSize($id, $name, $type);
            
            // Invalidate sizes cache
            Cache::delete('shop:sizes:all');
            
            return ['status' => 'success', 'message' => 'Size updated successfully.'];
        }

        public function deleteSize(){
            $id = $_POST['id'] ?? null;

            if (!$id) {
                return ['status' => 'error', 'message' => 'Size ID is required.'];
            }

            Sizes::deleteSize($id);
            
            // Invalidate sizes cache
            Cache::delete('shop:sizes:all');
            
            return ['status' => 'success', 'message' => 'Size deleted successfully.'];
        }
    }