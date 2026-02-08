<?php 

    class Middleware{

        public static function requireRoles(array $roles): void{
            if (
                !isset($_SESSION['user_id'], $_SESSION['role']) || 
                !in_array($_SESSION['role'], 
                $roles, 
                true)) 
                {
                    redirect('login.php');
            }
        }
    }