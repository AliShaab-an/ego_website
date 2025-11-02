<?php  
    require_once __DIR__ . '/../config/path.php';
    require_once MODELS . 'Sizes.php';
    class SizesController{

        public function listSizes(){
            try{
                // If 'all' parameter is set, return all sizes without pagination (for dropdowns)
                if (isset($_GET['all']) && $_GET['all'] === 'true') {
                    $data = Sizes::getAll();
                    return [
                        'status' => 'success',
                        'data' => $data,
                        'total' => count($data)
                    ];
                }

                // Otherwise, use pagination (for table views)
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

            }catch (Exception $e) {
                return ['status' => 'error', 'message' => $e->getMessage()];
            }
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

            try {
                $existing = Sizes::findByNameAndType($name, $type);
                if ($existing) {
                    return [
                        'status'  => 'error',
                        'message' => "Size '{$name}' for type '{$type}' already exists."
                    ];
                }

                $id = Sizes::create($name, $type);

                return [
                    'status'  => 'success',
                    'id'      => $id,
                    'message' => 'Size added successfully.'
                ];

            } catch (Exception $e) {
                return [
                    'status'  => 'error',
                    'message' => 'Server error: ' . $e->getMessage()
                ];
            }
        }


        public function updateSize(){
            $id   = $_POST['id'] ?? null;
            $name = ucfirst(strtolower(trim($_POST['name']))) ?? '';
            $type = ucfirst(strtolower(trim($_POST['type']))) ?? '';

            if (empty($id) || empty($name) || empty($type)) {
                return ['status' => 'error', 'message' => 'All fields are required.'];
            }

            try {
                Sizes::updateSize($id, $name, $type);
                return ['status' => 'success', 'message' => 'Size updated successfully.'];
            } catch (Exception $e) {
                return ['status' => 'error', 'message' => $e->getMessage()];
            }
        }

        public function deleteSize(){
            $id = $_POST['id'] ?? null;

            if (!$id) {
                return ['status' => 'error', 'message' => 'Size ID is required.'];
            }

            try {
                Sizes::deleteSize($id);
                return ['status' => 'success', 'message' => 'Size deleted successfully.'];
            } catch (Exception $e) {
                return ['status' => 'error', 'message' => $e->getMessage()];
            }
        }
    }