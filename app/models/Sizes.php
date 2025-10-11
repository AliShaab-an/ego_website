<?php 

    require_once __DIR__ . '/../core/DB.php';

    class Sizes{
        public static function getAll(){
            return DB::query("SELECT * FROM sizes ORDER BY name ASC")->fetchAll();
        }

        public static function getById($id) {
            return DB::query("SELECT * FROM sizes WHERE id = ?", [$id])->fetch();
        }

        public static function create($name, $type){
            try {
                $name = ucfirst(strtolower(trim($name)));
                $type = ucfirst(strtolower(trim($type)));

                DB::query(
                    "INSERT INTO sizes (name, type) VALUES (?,?)",
                    [$name, $type]
                );

                return DB::getConnection()->lastInsertId();
            } catch (PDOException $e) {
                throw new Exception("Failed to create size: " . $e->getMessage());
            }
        }

        public static function delete($id){
            DB::query("DELETE FROM sizes WHERE id = ?", [$id]);
        }

        public static function updateSize($id, $name, $type){
            try {
                $id   = intval($id);
                $name = ucfirst(strtolower(trim($name)));
                $type = ucfirst(strtolower(trim($type)));

                if ($id <= 0 || $name === '' || $type === '') {
                    throw new Exception("Invalid size data.");
                }

                // Update query
                DB::query(
                    "UPDATE sizes SET name = ?, type = ? WHERE id = ?",
                    [$name, $type, $id]
                );

                return true;
            } catch (PDOException $e) {
                throw new Exception("Failed to update size: " . $e->getMessage());
            }
        }


        public static function deleteSize($id){
            try {
                if (!is_numeric($id) || $id <= 0) {
                    throw new Exception("Invalid size ID.");
                }

                DB::query("DELETE FROM sizes WHERE id = ?", [$id]);
                return true;
            } catch (PDOException $e) {
                throw new Exception("Failed to delete size: " . $e->getMessage());
            }
        }

        public static function findByNameAndType($name, $type){
            try {
                $name = ucfirst(strtolower(trim($name)));
                $type = ucfirst(strtolower(trim($type)));

                $stmt = DB::query(
                    "SELECT * FROM sizes WHERE name = ? AND type = ? LIMIT 1",
                    [$name, $type]
                );

                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                return $result ?: null;

            } catch (PDOException $e) {
                throw new Exception("Failed to check size: " . $e->getMessage());
            }
        }
    }