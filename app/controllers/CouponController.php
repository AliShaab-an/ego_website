<?php 
    require_once __DIR__ . '/../models/Coupon.php';


    class CouponController{

        public function addCoupon(){
            $code = strtoupper(trim($_POST['code'] ?? ''));
            $discountType = $_POST['discount_type'] ?? '';
            $discountValue = trim($_POST['discount_value'] ?? '');
            $startDate = $_POST['start_date'] ?? '';
            $endDate = $_POST['end_date'] ?? '';
            $minOrderValue = trim($_POST['min_order_value'] ?? 0);
            $isActive = isset($_POST['is_active']) ? 1 : 0;

            // === Validation ===
            if ($code === '' || $discountType === '' || $discountValue === '') {
                return ['status' => 'error', 'message' => 'All required fields must be filled.'];
            }
            if (!is_numeric($discountValue) || $discountValue <= 0) {
                return ['status' => 'error', 'message' => 'Invalid discount value.'];
            }

            try {
                $existing = Coupon::findByCode($code);
                if ($existing) {
                    return ['status' => 'error', 'message' => 'Coupon code already exists.'];
                }

                $id = Coupon::create($code, $discountType, $discountValue, $startDate, $endDate, $minOrderValue, $isActive);
                return ['status' => 'success', 'id' => $id, 'message' => 'Coupon added successfully.'];
            } catch (Exception $e) {
                return ['status' => 'error', 'message' => $e->getMessage()];
            }
        }

        public function listCoupons(){    
            try {
                $data = Coupon::getAll();
                return ['status' => 'success', 'data' => $data];
            } catch (Exception $e) {
                return ['status' => 'error', 'message' => $e->getMessage()];
            }
        }

        public function getCoupon(){
            $id = $_GET['id'] ?? null;
            if (!$id) {
                return ['status' => 'error', 'message' => 'Missing coupon ID'];
            }

            try {
                $coupon = Coupon::findById($id);
                if ($coupon) {
                    return ['status' => 'success', 'data' => $coupon];
                } else {
                    return ['status' => 'error', 'message' => 'Coupon not found'];
                }
            } catch (Exception $e) {
                return ['status' => 'error', 'message' => $e->getMessage()];
            }
        }

        public function updateCoupon(){
            $id = $_POST['id'] ?? null;
            $code = strtoupper(trim($_POST['code'] ?? ''));
            $discountType = $_POST['discount_type'] ?? '';
            $discountValue = trim($_POST['discount_value'] ?? '');
            $startDate = $_POST['start_date'] ?? '';
            $endDate = $_POST['end_date'] ?? '';
            $minOrderValue = trim($_POST['min_order_value'] ?? 0);
            $isActive = isset($_POST['is_active']) ? 1 : 0;

            if (!$id || $code === '') {
                return ['status' => 'error', 'message' => 'Missing required fields.'];
            }

            try {
                Coupon::update($id, $code, $discountType, $discountValue, $startDate, $endDate, $minOrderValue, $isActive);
                return ['status' => 'success', 'message' => 'Coupon updated successfully.'];
            } catch (Exception $e) {
                return ['status' => 'error', 'message' => $e->getMessage()];
            }
        }

    public function deleteCoupon(){
        $id = $_POST['id'] ?? null;
        if (!$id) {
            return ['status' => 'error', 'message' => 'Invalid coupon ID'];
        }

        try {
            Coupon::delete($id);
            return ['status' => 'success', 'message' => 'Coupon deleted successfully.'];
        } catch (Exception $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }
}

