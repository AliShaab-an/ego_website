<?php

    require_once __DIR__ . '/../models/Shipping.php';


    class shippingController{

        public function addShipping(){
            $name = isset($_POST['region_name']) ? strtoupper(trim($_POST['region_name'])) : '';

            $fee = isset($_POST['fee_per_kg']) ? trim($_POST['fee_per_kg']) : '';

            if($name === ''){
                return ['status' => 'error', 'message' => 'Region name required'];
            }
            if ($fee === '') {
                return ['status' => 'error', 'message' => 'Fee is required.'];
            }

            try{
                $existing = Shipping::findByName($name);

                if ($existing) {
                    return ['status' => 'error', 'message' => 'Region already exists.'];
                }

                $id = Shipping::createShipping($name,$fee);

                return [
                    'status'  => 'success',
                    'id'      => $id,
                    'message' => 'Region added successfully.'
                ];
            }catch(Exception $e){
                return ['status' => 'error', 'message' => $e->getMessage()];
            }
        }

        public function listShipping(){

            try{
                $shipping = Shipping::getAll();
                return ['status' => 'success', 'data' => $shipping];
            }catch(Exception $e){
                return ['status' => 'error', 'message' => $e->getMessage()];
            }
        }


        public function deleteShipping(){
            $id = $_POST['id'] ?? null;

            if (!$id) {
                return ['status' => 'error', 'message' => 'Region ID is required.'];
            }

            try {
                Shipping::deleteShipping($id);
                return ['status' => 'success', 'message' => 'Region deleted successfully.'];
            } catch (Exception $e) {
                return ['status' => 'error', 'message' => $e->getMessage()];
            }
        }

        public function updateShipping(){
            $id   = $_POST['id'] ?? null;
            $name = strtoupper(trim($_POST['region_name']));
            $fee  = trim($_POST['fee_per_kg']);

            if (empty($id) || empty($name) || empty($fee)) {
                return ['status' => 'error', 'message' => 'All fields are required.'];
            }

            try {
                Shipping::updateShipping($id, $name, $fee);
                return ['status' => 'success', 'message' => 'Region updated successfully.'];
            } catch (Exception $e) {
                return ['status' => 'error', 'message' => $e->getMessage()];
            }
        }

        public function toggleStatus(){
            $id = $_POST['id'] ?? null;
            $status = $_POST['status'] ?? null;

            if (!$id || !is_numeric($id)) {
                return ['status' => 'error', 'message' => 'Valid region ID is required'];
            }

            if ($status === null || !in_array($status, ['0', '1'])) {
                return ['status' => 'error', 'message' => 'Valid status is required'];
            }

            try {
                Shipping::toggleStatus($id, $status);
                $statusText = $status == 1 ? 'activated' : 'deactivated';
                return [
                    'status' => 'success', 
                    'message' => "Region {$statusText} successfully!",
                    'new_status' => (int)$status
                ];
            } catch (Exception $e) {
                return ['status' => 'error', 'message' => $e->getMessage()];
            }
        }

    }