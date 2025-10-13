<?php 

    require_once __DIR__ . '/../core/DB.php';

    class Colors{

        public static function getAllColors(){
            return DB::query("SELECT * FROM colors ORDER BY name ASC")-> fetchAll();
        }

        public static function getPaginated($limit, $offset) {
            try{
                $limit = (int)$limit;
                $offset = (int)$offset;
                $stmt = DB::query("SELECT * FROM colors ORDER BY id DESC LIMIT $limit OFFSET $offset");
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }catch(PDOException $e){
                throw new Exception("Failed to fetch colors: " . $e->getMessage());
            }
        }


        public static function countAll() {
            try{
                $stmt = DB::query("SELECT COUNT(*) AS count FROM colors");
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                return (int)$row['count'];
            }catch(PDOException $e){
                throw new Exception("Failed to count colors: " . $e->getMessage());
            }
        }

        public static function getById($id){
            return DB::query("SELECT * FROM colors WHERE id = ?", [$id])->fetch();
        }

        public static function createColor($name,$hex=null){
            try{
                DB::query("INSERT INTO colors (name, hex_code) VALUES (?, ?)", [$name, $hex]);
            return DB::getConnection()->lastInsertId();
            }catch(PDOException $e){
                throw new Exception("Failed to create color: " . $e->getMessage());
            }
            
        }

        public static function deleteColor($id){
            try {
                if (!is_numeric($id) || $id <= 0) {
                    throw new Exception("Invalid color ID.");
                }

                $deleted = DB::query("DELETE FROM colors WHERE id = ?", [$id]);

                if ($deleted === 0) {
                    throw new Exception("Color not found or already deleted.");
                }
                return true;

            } catch (PDOException $e) {
                throw new Exception("Failed to delete color: " . $e->getMessage());
            }
        }

        public static function updateColor($id, $name, $hex){
            try {
                $id   = intval($id);
                $name = trim($name);
                $hex  = strtoupper(trim($hex));

                if ($id <= 0 || $name === '' || $hex === '') {
                    throw new Exception("Invalid color data.");
                }

                // Optional: validate hex format
                if (!preg_match('/^#([A-F0-9]{6})$/', $hex)) {
                    throw new Exception("Invalid hex color format.");
                }

                // Update record
                DB::query(
                    "UPDATE colors SET name = ?, hex_code = ? WHERE id = ?",
                    [$name, $hex, $id]
                );

                return true;

            } catch (PDOException $e) {
                throw new Exception("Failed to update color: " . $e->getMessage());
            }
        }

        public static function findByName($name){
            $name = ucfirst(strtolower(trim($name)));
            try {
                $stmt = DB::query("SELECT * FROM colors WHERE name = ? LIMIT 1", [$name]);
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                return $result ?: null;
            } catch (PDOException $e) {
                throw new Exception("Failed to check color name: " . $e->getMessage());
            }
        }
    }