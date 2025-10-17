<?php 

    require_once __DIR__ . '/../core/DB.php';

    class Shipping{

        public static function createShipping($name, $fee){
            try{
                DB::query("INSERT INTO shipping_regions (region_name, fee_per_kg) VALUES (?, ?)", [$name, $fee]);
                return DB::getConnection()->lastInsertId();
            }catch(PDOException $e){
                throw new Exception("Failed to create shipping: " . $e->getMessage());
            }
        }

        public static function findByName($name){
            try{
                $stmt = DB::query("SELECT * FROM shipping_regions WHERE region_name = ?", [$name]);
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                return $result ?: null;
            }catch(PDOException $e){
                throw new Exception("Failed to check shipping name: " . $e->getMessage());
            }
        }

        public static function getAll() {
            try{
                $stmt =  DB::query("SELECT * FROM shipping_regions ORDER BY region_name ASC");
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }catch(PDOException $e){
                throw new Exception("Failed to fetch shipping fees: " . $e->getMessage());
            }
        }

        public static function updateShipping($id, $name, $fee) {
            try{
                $id   = intval($id);
                $name = trim($name);
                $fee  = trim($fee);

                if ($id <= 0 || $name === '' || $fee === '') {
                    throw new Exception("Invalid shipping data.");
                }


                DB::query("UPDATE shipping_regions SET region_name = ?, fee_per_kg = ? WHERE id = ?", [$name, $fee, $id]);
                return true;
            }catch(PDOException $e){
                throw new Exception("Failed to update shipping: " . $e->getMessage());
            }
        }

        public static function deleteShipping($id) {
            try{
                if (!is_numeric($id) || $id <= 0) {
                    throw new Exception("Invalid shipping ID.");
                }
                $deleted = DB::query("DELETE FROM shipping_regions WHERE id = ?", [$id]);

                if ($deleted === 0) {
                    throw new Exception("Region not found or already deleted.");
                }
                return true;

            }catch(PDOException $e){
                throw new Exception("Failed to delete shipping: " . $e->getMessage());
            }
        }
    }
    