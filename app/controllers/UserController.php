<?php 

    require_once __DIR__ . '/../models/User.php';
    class UserController{

        

        public function listCustomers(){
            try{
                $customers = User::getAllCustomers();
                return ['status' => 'success', 'data' => $customers];
            }catch(Throwable $e){
                return ['status' => 'error', 'message' => $e->getMessage()];
            } 
        }

        public function listCustomersCountLast7Days(){
            try{
                $customersCount = User::getCustomersCountLast7Days();
                return ['status' => 'success', 'data' => $customersCount];
            }catch(Throwable $e){
                return ['status' => 'error', 'message' => $e->getMessage()];
            }
        }

        public function listAdmins(){
            try{
                $admins = User::getAllAdmins();
                return ['status' => 'success', 'data' => $admins];
            }catch(Throwable $e){
                return ['status' => 'error', 'message' => $e->getMessage()];
            }
        }

        // public function listTotalCustomers(){
        //     try{
        //         $customersCount = User::getAllCustomers();
        //         return ['status' => 'success', 'data' => $customersCount];
        //     }catch(Throwable $e){
        //         return ['status' => 'error', 'message' => $e->getMessage()];
        //     }
        // }
    }