<?php 

    class UserController{

        public function customersPage(){
            include __DIR__ . '/../views/backend/customers.php';
        }

        public function adminsPage(){
            include __DIR__ . '/../views/backend/admins.php';
        }
    }