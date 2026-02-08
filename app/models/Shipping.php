<?php 

    class Shipping{

        public static function createShipping($name, $fee){
            DB::query("INSERT INTO shipping_regions (region_name, fee_per_kg) VALUES (?, ?)", [$name, $fee]);
            return DB::getConnection()->lastInsertId();
        }

        public static function findByName($name){
            $stmt = DB::query("SELECT * FROM shipping_regions WHERE region_name = ?", [$name]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ?: null;
        }

        public static function getAll() {
            $stmt =  DB::query("SELECT * FROM shipping_regions WHERE is_active = 1 ORDER BY region_name ASC");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public static function updateShipping($id, $name, $fee) {
            $id   = intval($id);
            $name = trim($name);
            $fee  = trim($fee);

            if ($id <= 0 || $name === '' || $fee === '') {
                throw new Exception("Invalid shipping data.");
            }

            DB::query("UPDATE shipping_regions SET region_name = ?, fee_per_kg = ? WHERE id = ?", [$name, $fee, $id]);
            return true;
        }

        public static function deleteShipping($id) {
            if (!is_numeric($id) || $id <= 0) {
                throw new Exception("Invalid shipping ID.");
            }
            $deleted = DB::query("DELETE FROM shipping_regions WHERE id = ?", [$id]);

            if ($deleted === 0) {
                throw new Exception("Region not found or already deleted.");
            }
            return true;
        }

        public static function toggleStatus($id, $status) {
            if (!is_numeric($id) || $id <= 0) {
                throw new Exception("Invalid shipping ID.");
            }

            if (!in_array($status, ['0', '1', 0, 1])) {
                throw new Exception("Invalid status value.");
            }

            $updated = DB::query("UPDATE shipping_regions SET is_active = ? WHERE id = ?", [$status, $id]);

            if ($updated === false) {
                throw new Exception("Failed to update region status.");
            }
            return true;
        }
    }
    