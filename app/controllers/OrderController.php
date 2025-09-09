<?php 


    class OrderController{
        public function ordersPage() {
        // later fetch from DB
        $orders = [
            ['id' => 'ORD123', 'product' => 'Product 1', 'date' => '2023-10-01', 'price' => 100, 'payment' => 'Paid', 'status' => 'Shipped'],
            ['id' => 'ORD124', 'product' => 'Product 2', 'date' => '2023-10-05', 'price' => 50, 'payment' => 'Unpaid', 'status' => 'Pending']
        ];
        include __DIR__ . '/../views/backend/orders.php';
    }
    }